<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Page;

/**
 * Reads one YAML file per page from database/files/pages/ and writes them to the
 * database. The files are the source of truth — running this repeatedly is safe.
 *
 * Replaces a CSV that carried JSON inside its cells, which was effectively
 * uneditable by hand and produced unreadable diffs.
 */
class ImportPagesCommand extends ImportCommand
{
    protected $signature = 'pages:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/pages}';

    protected $description = 'Import page SEO metadata from database/files/pages/*.yaml';

    public function handle(): int
    {
        $files = $this->yamlFiles();

        if ($files === []) {
            $this->components->warn('No page files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = $this->isDryRun();
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
            $this->removeOrphans(Page::query(), 'key', $keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d page(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/pages';
    }

    /**
     * @return array{key: string, robots: string, title: array<string, string>, description: array<string, string>, image: ?string}|null
     */
    private function parse(string $path): ?array
    {
        $parsed = $this->parseYamlFile($path);

        if ($parsed === null) {
            return null;
        }

        $key = $this->string($parsed['key'] ?? '') ?: pathinfo($path, PATHINFO_FILENAME);
        $robots = $this->string($parsed['robots'] ?? '');

        if ($robots === '') {
            $this->components->error(basename($path).' has no "robots" — skipped.');

            return null;
        }

        // Sanitised up front, so the completeness check below sees exactly the
        // locales that survive as usable strings — not keys that only look present.
        $titles = $this->localizedMap($parsed['title'] ?? null);
        $descriptions = $this->localizedMap($parsed['description'] ?? null);

        $locales = array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases());

        // Checked per field: a single array_diff against both at once would accept a
        // page whose title is German-only and whose description is English-only.
        foreach (['title' => $titles, 'description' => $descriptions] as $field => $values) {
            $missing = $this->missingLocales($locales, $values);

            if ($missing !== []) {
                $this->components->error(sprintf(
                    '%s is missing a %s for %s.',
                    basename($path),
                    $field,
                    implode(', ', $missing)
                ));

                return null;
            }
        }

        return [
            'key' => $key,
            'robots' => $robots,
            'title' => $titles,
            'description' => $descriptions,
            'image' => $this->nullableString($parsed['image'] ?? null),
        ];
    }
}
