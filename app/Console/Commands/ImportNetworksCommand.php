<?php

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads one YAML file per network partner from database/files/networks/ and writes
 * them to the database. The files are the source of truth — running this repeatedly
 * is safe.
 */
class ImportNetworksCommand extends Command
{
    protected $signature = 'networks:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/networks}';

    protected $description = 'Import network partners from database/files/networks/*.yaml';

    public function handle(): int
    {
        $files = $this->files();

        if ($files === []) {
            $this->components->warn('No network files found under '.$this->basePath().'.');

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
                $exists = Network::where('key', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            Network::updateOrCreate(['key' => $key], [
                'name' => $data['name'],
                'category' => $data['category']->value,
                'status' => $data['status']->value,
                'cover_url' => $data['cover_url'],
                'tier_label' => $data['tier_label'],
                'excerpt' => $data['excerpt'],
                'website' => $data['website'],
                'since_year' => $data['since_year'],
                'until_year' => $data['until_year'],
                'page_slug' => $data['page_slug'],
                'published' => $data['published'],
                'sort' => $data['sort'],
            ]);

            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans($keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d network(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== '' ? rtrim($override, '/') : database_path('files/networks');
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
     * @return array{key: string, category: NetworkCategoryEnum, status: NetworkStatusEnum, sort: int, cover_url: ?string, website: ?string, since_year: ?int, until_year: ?int, page_slug: ?string, published: bool, name: array<string, string>, tier_label: array<string, string>, excerpt: array<string, string>}|null
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
        $names = is_array($parsed['name'] ?? null) ? $parsed['name'] : [];

        $missing = array_diff(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
            array_keys($names)
        );

        if ($missing !== []) {
            $this->components->error(sprintf('%s is missing a name for %s.', basename($path), implode(', ', $missing)));

            return null;
        }

        $category = NetworkCategoryEnum::tryFrom($this->string($parsed['category'] ?? ''));

        if ($category === null) {
            $this->components->error(basename($path).' has no valid "category".');

            return null;
        }

        return [
            'key' => $key,
            'category' => $category,
            'status' => NetworkStatusEnum::tryFrom($this->string($parsed['status'] ?? '')) ?? NetworkStatusEnum::ACTIVE,
            'sort' => is_int($parsed['sort'] ?? null) ? $parsed['sort'] : 0,
            'cover_url' => $this->nullableString($parsed['cover_url'] ?? null),
            'website' => $this->nullableString($parsed['website'] ?? null),
            'since_year' => $this->nullableInt($parsed['since_year'] ?? null),
            'until_year' => $this->nullableInt($parsed['until_year'] ?? null),
            'page_slug' => $this->nullableString($parsed['page_slug'] ?? null),
            'published' => (bool) ($parsed['published'] ?? true),
            'name' => array_map(fn (mixed $value): string => $this->string($value), $names),
            'tier_label' => $this->localizedMap($parsed['tier_label'] ?? null),
            'excerpt' => $this->localizedMap($parsed['excerpt'] ?? null),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function localizedMap(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];

        foreach ($value as $locale => $text) {
            if (is_string($locale) && is_string($text) && $text !== '') {
                $result[$locale] = $text;
            }
        }

        return $result;
    }

    /**
     * A network removed from the repository must disappear from the site too,
     * otherwise the files stop being the source of truth.
     *
     * @param  array<int, string>  $keys
     */
    private function removeOrphans(array $keys): void
    {
        $orphans = Network::whereNotIn('key', $keys)->get();

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

    private function nullableInt(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        return is_string($value) && ctype_digit($value) ? (int) $value : null;
    }

    private function nullableString(mixed $value): ?string
    {
        $string = $this->string($value);

        return $string === '' ? null : $string;
    }
}
