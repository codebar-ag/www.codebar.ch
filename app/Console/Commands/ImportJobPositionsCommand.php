<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\JobPositionStatusEnum;
use App\Enums\LocaleEnum;
use App\Models\JobPosition;

/**
 * Reads one YAML file per position from database/files/jobs/ and writes them to the
 * job_positions table. The files are the source of truth — running this repeatedly is safe.
 */
class ImportJobPositionsCommand extends ImportCommand
{
    protected $signature = 'jobs:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/jobs}';

    protected $description = 'Import job positions from database/files/jobs/*.yaml';

    public function handle(): int
    {
        $files = $this->yamlFiles();

        if ($files === []) {
            $this->components->warn('No job position files found under '.$this->basePath().'.');

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

            $expected = $key.'.'.pathinfo($path, PATHINFO_EXTENSION);

            if (basename($path) !== $expected) {
                $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
            }

            if ($dryRun) {
                $exists = JobPosition::where('key', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            JobPosition::updateOrCreate(['key' => $key], [
                'published' => $data['published'],
                'sort' => $data['sort'],
                'status' => $data['status'],
                'route_name' => $data['route_name'],
                'title' => $data['title'],
                'teaser' => $data['teaser'],
            ]);

            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans(JobPosition::query(), 'key', $keys);
            JobPosition::clearPublishedCache();
        }

        $this->newLine();
        $this->components->info(sprintf('%d position(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/jobs';
    }

    /**
     * @return array{key: string, published: bool, sort: int, status: JobPositionStatusEnum, route_name: string|null, title: array<string, string>, teaser: array<string, string>}|null
     */
    private function parse(string $path): ?array
    {
        $parsed = $this->parseYamlFile($path);

        if ($parsed === null) {
            return null;
        }

        $key = $this->string($parsed['key'] ?? '') ?: pathinfo($path, PATHINFO_FILENAME);
        $status = JobPositionStatusEnum::tryFrom($this->string($parsed['status'] ?? ''));

        if ($status === null) {
            $statuses = implode(', ', array_column(JobPositionStatusEnum::cases(), 'value'));
            $this->components->error(basename($path).' has no valid "status" — expected one of: '.$statuses.'.');

            return null;
        }

        $title = $this->localizedMap($parsed['title'] ?? null);
        $missing = $this->missingLocales(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
            $title
        );

        if ($missing !== []) {
            $this->components->error(sprintf(
                '%s is missing a "title" for %s — every position needs both languages.',
                basename($path),
                implode(', ', $missing)
            ));

            return null;
        }

        return [
            'key' => $key,
            'published' => (bool) ($parsed['published'] ?? false),
            'sort' => is_int($parsed['sort'] ?? null) ? $parsed['sort'] : 0,
            'status' => $status,
            'route_name' => $this->nullableString($parsed['route_name'] ?? null),
            'title' => $title,
            'teaser' => $this->localizedMap($parsed['teaser'] ?? null),
        ];
    }
}
