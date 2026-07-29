<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;

/**
 * Reads one YAML file per network partner from database/files/networks/ and writes
 * them to the database. The files are the source of truth — running this repeatedly
 * is safe.
 */
class ImportNetworksCommand extends ImportCommand
{
    protected $signature = 'networks:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/networks}';

    protected $description = 'Import network partners from database/files/networks/*.yaml';

    public function handle(): int
    {
        $files = $this->yamlFiles();

        if ($files === []) {
            $this->components->warn('No network files found under '.$this->basePath().'.');

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
            $this->removeOrphans(Network::query(), 'key', $keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d network(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/networks';
    }

    /**
     * @return array{key: string, category: NetworkCategoryEnum, status: NetworkStatusEnum, sort: int, cover_url: ?string, website: ?string, since_year: ?int, until_year: ?int, page_slug: ?string, published: bool, name: array<string, string>, tier_label: array<string, string>, excerpt: array<string, string>}|null
     */
    private function parse(string $path): ?array
    {
        $parsed = $this->parseYamlFile($path);

        if ($parsed === null) {
            return null;
        }

        $key = $this->string($parsed['key'] ?? '') ?: pathinfo($path, PATHINFO_FILENAME);
        // Sanitised up front, so the completeness check below sees exactly the
        // locales that survive as usable strings — not keys that only look present.
        $names = $this->localizedMap($parsed['name'] ?? null);

        $missing = $this->missingLocales(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
            $names
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
            'name' => $names,
            'tier_label' => $this->localizedMap($parsed['tier_label'] ?? null),
            'excerpt' => $this->localizedMap($parsed['excerpt'] ?? null),
        ];
    }
}
