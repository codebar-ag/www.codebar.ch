<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Contact;

/**
 * Reads one YAML file per person from database/files/team/ and writes them to the
 * contacts table. The files are the source of truth — running this repeatedly is safe.
 *
 * Replaces a semicolon-separated CSV that carried JSON inside its cells, which was
 * effectively uneditable by hand and produced unreadable diffs.
 */
class ImportTeamCommand extends ImportCommand
{
    protected $signature = 'team:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/team}';

    protected $description = 'Import team members from database/files/team/*.yaml';

    public function handle(): int
    {
        $files = $this->yamlFiles();

        if ($files === []) {
            $this->components->warn('No team files found under '.$this->basePath().'.');

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

            // The file name is the key, so the directory listing reads as the team list.
            $expected = $key.'.'.pathinfo($path, PATHINFO_EXTENSION);

            if (basename($path) !== $expected) {
                $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
            }

            if ($dryRun) {
                $exists = Contact::where('key', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            Contact::updateOrCreate(['key' => $key], [
                'name' => $data['name'],
                'published' => $data['published'],
                'sort' => $data['sort'],
                'image' => $data['image'],
                'sections' => $data['sections'],
                'icons' => $data['icons'],
            ]);

            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans(Contact::query(), 'key', $keys);
            Contact::clearPublishedCache();
        }

        $this->newLine();
        $this->components->info(sprintf('%d person(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/team';
    }

    /**
     * @return array{key: string, name: string, published: bool, sort: int, image: string, sections: array<string, mixed>, icons: array<string, mixed>}|null
     */
    private function parse(string $path): ?array
    {
        $parsed = $this->parseYamlFile($path);

        if ($parsed === null) {
            return null;
        }

        $key = $this->string($parsed['key'] ?? '') ?: pathinfo($path, PATHINFO_FILENAME);
        $name = $this->string($parsed['name'] ?? '');

        if ($name === '') {
            $this->components->error(basename($path).' has no "name" — skipped.');

            return null;
        }

        return [
            'key' => $key,
            'name' => $name,
            'published' => (bool) ($parsed['published'] ?? false),
            'sort' => is_int($parsed['sort'] ?? null) ? $parsed['sort'] : 0,
            'image' => $this->string($parsed['image'] ?? ''),
            // Each section carries its own key, which the DTO reads back — filling it
            // here keeps the YAML free of a value that only repeats the mapping key.
            'sections' => $this->sections($parsed['sections'] ?? []),
            'icons' => $this->localizedMap($parsed['icons'] ?? null),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function sections(mixed $sections): array
    {
        if (! is_array($sections)) {
            return [];
        }

        $result = [];

        foreach ($sections as $section => $definition) {
            if (! is_string($section)) {
                continue;
            }

            $entry = ['key' => $section];

            if (is_array($definition) && isset($definition['role']) && is_array($definition['role'])) {
                $entry['role'] = $definition['role'];
            }

            $result[$section] = $entry;
        }

        return $result;
    }
}
