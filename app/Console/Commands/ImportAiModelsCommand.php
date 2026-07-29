<?php

namespace App\Console\Commands;

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads one YAML file per model from database/files/ai_models/ and writes them to the
 * database. The files are the source of truth — running this repeatedly is safe.
 *
 * `replaced_by` in the front matter references another model by its file key, not a
 * database id — ids are not stable across a fresh import, so the link is resolved by
 * key in a second pass once every row exists.
 */
class ImportAiModelsCommand extends Command
{
    protected $signature = 'ai-models:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/ai_models}';

    protected $description = 'Import AI models from database/files/ai_models/*.yaml';

    public function handle(): int
    {
        $files = $this->files();

        if ($files === []) {
            $this->components->warn('No AI model files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $imported = 0;
        $skipped = 0;
        $parsedByKey = [];

        foreach ($files as $path) {
            $data = $this->parse($path);

            if ($data === null) {
                $skipped++;

                continue;
            }

            $expected = $data['key'].'.yaml';

            if (basename($path) !== $expected) {
                $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
            }

            $parsedByKey[$data['key']] = $data;

            if ($dryRun) {
                $exists = AiModel::where('name', $data['name'])->exists();
                $this->components->twoColumnDetail($data['key'], $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
            }

            $imported++;
        }

        if ($dryRun) {
            $this->newLine();
            $this->components->info(sprintf('%d model(s) pending, %d skipped.', $imported, $skipped));

            return $skipped > 0 ? self::FAILURE : self::SUCCESS;
        }

        $idsByKey = [];

        foreach ($parsedByKey as $key => $data) {
            $model = AiModel::updateOrCreate(
                ['name' => $data['name']],
                [
                    'category' => $data['category']->value,
                    'order' => $data['order'],
                    'provider' => $data['provider'],
                    'ram' => $data['ram'],
                    'license' => $data['license'],
                    'role' => $data['role'],
                    'link_label' => $data['link_label'],
                    'link_url' => $data['link_url'],
                    'archived_at' => $data['archived_at'],
                ]
            );

            $idsByKey[$key] = $model->id;
            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
        }

        foreach ($parsedByKey as $key => $data) {
            if ($data['replaced_by'] === null) {
                continue;
            }

            $targetId = $idsByKey[$data['replaced_by']] ?? null;

            if ($targetId === null) {
                $this->components->error(sprintf('"%s" replaces an unknown model key "%s".', $key, $data['replaced_by']));

                continue;
            }

            AiModel::whereKey($idsByKey[$key])->update(['replaced_by_id' => $targetId]);
        }

        $this->removeOrphans(array_map(fn (array $data): string => $data['name'], $parsedByKey));

        $this->newLine();
        $this->components->info(sprintf('%d model(s) imported, %d skipped.', $imported, $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== '' ? rtrim($override, '/') : database_path('files/ai_models');
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
     * @return array{key: string, name: string, category: AiModelCategoryEnum, order: int, provider: ?string, ram: ?string, license: ?string, role: ?array<string, string>, link_label: ?string, link_url: ?string, archived_at: ?string, replaced_by: ?string}|null
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

        $category = AiModelCategoryEnum::tryFrom($this->string($parsed['category'] ?? ''));

        if ($category === null) {
            $this->components->error(basename($path).' has no valid "category" — skipped.');

            return null;
        }

        return [
            'key' => $key,
            'name' => $name,
            'category' => $category,
            'order' => is_int($parsed['order'] ?? null) ? $parsed['order'] : 0,
            'provider' => $this->nullableString($parsed['provider'] ?? null),
            'ram' => $this->nullableString($parsed['ram'] ?? null),
            'license' => $this->nullableString($parsed['license'] ?? null),
            'role' => $this->role($parsed['role'] ?? null),
            'link_label' => $this->nullableString($parsed['link_label'] ?? null),
            'link_url' => $this->nullableString($parsed['link_url'] ?? null),
            'archived_at' => $this->nullableString($parsed['archived_at'] ?? null),
            'replaced_by' => $this->nullableString($parsed['replaced_by'] ?? null),
        ];
    }

    /**
     * @return array<string, string>|null
     */
    private function role(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $result = [];

        foreach ($value as $locale => $text) {
            if (is_string($locale) && is_string($text) && $text !== '') {
                $result[$locale] = $text;
            }
        }

        return $result === [] ? null : $result;
    }

    /**
     * A model removed from the repository must disappear from the catalogue too,
     * otherwise the files stop being the source of truth.
     *
     * @param  array<int, string>  $names
     */
    private function removeOrphans(array $names): void
    {
        $orphans = AiModel::whereNotIn('name', $names)->get();

        foreach ($orphans as $orphan) {
            $this->components->twoColumnDetail($orphan->name, '<fg=red>removed</>');
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
