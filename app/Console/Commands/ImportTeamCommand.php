<?php

namespace App\Console\Commands;

use App\Models\Contact;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads one YAML file per person from database/files/team/ and writes them to the
 * contacts table. The files are the source of truth — running this repeatedly is safe.
 *
 * Replaces a semicolon-separated CSV that carried JSON inside its cells, which was
 * effectively uneditable by hand and produced unreadable diffs.
 */
class ImportTeamCommand extends Command
{
    protected $signature = 'team:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/team}';

    protected $description = 'Import team members from database/files/team/*.yaml';

    public function handle(): int
    {
        $files = $this->files();

        if ($files === []) {
            $this->components->warn('No team files found under '.$this->basePath().'.');

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
            $this->removeOrphans($keys);
            Contact::clearPublishedCache();
        }

        $this->newLine();
        $this->components->info(sprintf('%d person(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== '' ? rtrim($override, '/') : database_path('files/team');
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
     * @return array{key: string, name: string, published: bool, sort: int, image: string, sections: array<string, mixed>, icons: array<string, mixed>}|null
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
            'icons' => $this->icons($parsed['icons'] ?? null),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function icons(mixed $icons): array
    {
        if (! is_array($icons)) {
            return [];
        }

        $result = [];

        foreach ($icons as $name => $value) {
            if (is_string($name) && is_string($value) && $value !== '') {
                $result[$name] = $value;
            }
        }

        return $result;
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

    /**
     * A person removed from the repository must disappear from the site too, otherwise
     * the files stop being the source of truth.
     *
     * @param  array<int, string>  $keys
     */
    private function removeOrphans(array $keys): void
    {
        $orphans = Contact::whereNotIn('key', $keys)->get();

        foreach ($orphans as $orphan) {
            $this->components->twoColumnDetail($orphan->key, '<fg=red>removed</>');
            $orphan->delete();
        }
    }

    private function string(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}
