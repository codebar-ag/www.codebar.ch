<?php

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Page;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads one YAML file per page from database/files/pages/ and writes them to the
 * database. The files are the source of truth — running this repeatedly is safe.
 *
 * Replaces a CSV that carried JSON inside its cells, which was effectively
 * uneditable by hand and produced unreadable diffs.
 */
class ImportPagesCommand extends Command
{
    protected $signature = 'pages:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/pages}';

    protected $description = 'Import page SEO metadata from database/files/pages/*.yaml';

    public function handle(): int
    {
        $files = $this->files();

        if ($files === []) {
            $this->components->warn('No page files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $imported = 0;
        $skipped = 0;
        $keys = [];

        foreach ($files as $path) {
            $data = $this->parse($path);

            if ($data === null) {
                $skipped++;

                continue;
            }

            $key = $data['key'];
            $keys[] = $key;

            $expected = $key.'.yaml';

            if (basename($path) !== $expected) {
                $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
            }

            if ($dryRun) {
                $exists = Page::where('key', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            Page::updateOrCreate(['key' => $key], [
                'robots' => $data['robots'],
                'title' => $data['title'],
                'description' => $data['description'],
                'image' => $data['image'],
            ]);

            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans($keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d page(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== '' ? rtrim($override, '/') : database_path('files/pages');
    }

    /**
     * @return array<int, string>
     */
    private function files(): array
    {
        $base = $this->basePath();

        if (! is_dir($base)) {
            return [];
        }

        $files = array_merge(glob($base.'/*.yaml') ?: [], glob($base.'/*.yml') ?: []);
        sort($files);

        return $files;
    }

    /**
     * @return array{key: string, robots: string, title: array<string, string>, description: array<string, string>, image: ?string}|null
     */
    private function parse(string $path): ?array
    {
        try {
            $parsed = Yaml::parseFile($path);
        } catch (ParseException $exception) {
            $this->components->error(basename($path).': '.$exception->getMessage());

            return null;
        }

        if (! is_array($parsed)) {
            $this->components->error(basename($path).' is not a YAML mapping — skipped.');

            return null;
        }

        $key = $this->string($parsed['key'] ?? '') ?: pathinfo($path, PATHINFO_FILENAME);
        $robots = $this->string($parsed['robots'] ?? '');

        if ($robots === '') {
            $this->components->error(basename($path).' has no "robots" — skipped.');

            return null;
        }

        $titles = is_array($parsed['title'] ?? null) ? $parsed['title'] : [];
        $descriptions = is_array($parsed['description'] ?? null) ? $parsed['description'] : [];

        $missing = array_diff(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
            array_keys($titles),
            array_keys($descriptions)
        );

        if ($missing !== []) {
            $this->components->error(sprintf(
                '%s is missing a title or description for %s.',
                basename($path),
                implode(', ', $missing)
            ));

            return null;
        }

        return [
            'key' => $key,
            'robots' => $robots,
            'title' => array_map(fn (mixed $value): string => $this->string($value), $titles),
            'description' => array_map(fn (mixed $value): string => $this->string($value), $descriptions),
            'image' => $this->nullableString($parsed['image'] ?? null),
        ];
    }

    /**
     * A page removed from the repository must disappear from the site too, otherwise
     * the files stop being the source of truth.
     *
     * @param  array<int, string>  $keys
     */
    private function removeOrphans(array $keys): void
    {
        $orphans = Page::whereNotIn('key', $keys)->get();

        foreach ($orphans as $orphan) {
            $this->components->twoColumnDetail($orphan->key, '<fg=red>removed</>');
            $orphan->delete();
        }
    }

    private function string(mixed $value): string
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return trim((string) $value);
        }

        return '';
    }

    private function nullableString(mixed $value): ?string
    {
        $string = $this->string($value);

        return $string === '' ? null : $string;
    }
}
