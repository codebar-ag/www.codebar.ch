<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Shared machinery for the database/files/* importers.
 *
 * Every importer reads the same shape of input — either one YAML file per record or
 * one Markdown file per record and locale — coerces loosely typed YAML scalars, and
 * deletes rows whose file has disappeared. Those parts changed for the same reason in
 * all seven commands, so they live here; what stays in each command is the mapping
 * from front matter to columns, which is genuinely per-model.
 */
abstract class ImportCommand extends Command
{
    /** Directory under database/ this importer reads when --path is not given. */
    abstract protected function defaultPath(): string;

    protected function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== ''
            ? rtrim($override, '/')
            : database_path($this->defaultPath());
    }

    protected function isDryRun(): bool
    {
        return (bool) $this->option('dry-run');
    }

    /**
     * @return array<int, string>
     */
    protected function yamlFiles(): array
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
     * @return array<string, mixed>|null
     */
    protected function parseYamlFile(string $path): ?array
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

        return $this->stringKeyed($parsed);
    }

    /**
     * Splits a Markdown file into its YAML front matter and its body.
     *
     * @param  bool  $parseDateTime  Without it, a bare `published_at: 2026-07-28` arrives
     *                               as a Unix timestamp rather than a date.
     * @return array{front: array<string, mixed>, body: string}|null
     */
    protected function parseMarkdownFile(string $path, bool $parseDateTime = false): ?array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        $contents = str_replace("\r\n", "\n", $contents);

        if (! str_starts_with($contents, '---')) {
            $this->components->error(basename($path).' has no YAML front matter — skipped.');

            return null;
        }

        $parts = preg_split('/^---\s*$/m', $contents, 3);

        if ($parts === false || count($parts) < 3) {
            $this->components->error(basename($path).' has malformed front matter — skipped.');

            return null;
        }

        try {
            $front = Yaml::parse($parts[1], $parseDateTime ? Yaml::PARSE_DATETIME : 0);
        } catch (ParseException $exception) {
            $this->components->error(basename($path).': '.$exception->getMessage());

            return null;
        }

        return [
            'front' => is_array($front) ? $this->stringKeyed($front) : [],
            'body' => trim($parts[2]),
        ];
    }

    /**
     * Reads database/files/{importer}/{locale}/*.md into [key => [locale => document]].
     *
     * @param  array<int, string>  $locales
     * @return array<string, array<string, array{front: array<string, mixed>, body: string}>>
     */
    protected function readLocaleDocuments(array $locales, bool $parseDateTime = false): array
    {
        $documents = [];

        foreach ($locales as $locale) {
            $directory = $this->basePath().'/'.$locale;

            if (! is_dir($directory)) {
                continue;
            }

            foreach (glob($directory.'/*.md') ?: [] as $path) {
                $parsed = $this->parseMarkdownFile($path, $parseDateTime);

                if ($parsed === null) {
                    continue;
                }

                $key = $parsed['front']['key'] ?? null;

                if (! is_string($key) || $key === '') {
                    $this->components->error(basename($path).' has no "key" in its front matter — skipped.');

                    continue;
                }

                $this->checkFileName($path, $key, $parsed['front']);

                $documents[$key][$locale] = $parsed;
            }
        }

        ksort($documents);

        return $documents;
    }

    /**
     * Warns when a file breaks the naming convention. A mismatch is reported, not fatal —
     * renaming must never break an import.
     *
     * @param  array<string, mixed>  $front
     */
    protected function checkFileName(string $path, string $key, array $front): void
    {
        $expected = $key.'.md';

        if (basename($path) !== $expected) {
            $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
        }
    }

    /**
     * A record removed from the repository must disappear from the site too, otherwise
     * the files stop being the source of truth.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @param  array<int, string>  $keys
     */
    protected function removeOrphans(Builder $query, string $column, array $keys): void
    {
        foreach ($query->whereNotIn($column, $keys)->get() as $orphan) {
            $label = $orphan->getAttribute($column);

            $this->components->twoColumnDetail(is_scalar($label) ? (string) $label : '?', '<fg=red>removed</>');
            $orphan->delete();
        }
    }

    /**
     * Reports which locales a record is missing, so a half-translated file never
     * reaches the database as a record with an empty language.
     *
     * @param  array<int, string>  $required
     * @param  array<string, mixed>  $present
     * @return array<int, string>
     */
    protected function missingLocales(array $required, array $present): array
    {
        return array_values(array_diff($required, array_keys($present)));
    }

    /**
     * Narrows a YAML mapping to its string keys, which is what every caller assumes
     * and what PHPStan needs to see.
     *
     * @param  array<array-key, mixed>  $values
     * @return array<string, mixed>
     */
    protected function stringKeyed(array $values): array
    {
        $result = [];

        foreach ($values as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * A YAML mapping of locale => text, with blanks dropped.
     *
     * @return array<string, string>
     */
    protected function localizedMap(mixed $value): array
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
     * @return array<int, string>
     */
    protected function stringList(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        $result = [];

        foreach ($values as $value) {
            $string = $this->string($value);

            if ($string !== '') {
                $result[] = $string;
            }
        }

        return $result;
    }

    protected function string(mixed $value): string
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return trim((string) $value);
        }

        return '';
    }

    protected function nullableString(mixed $value): ?string
    {
        $string = $this->string($value);

        return $string === '' ? null : $string;
    }

    protected function nullableInt(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        return is_string($value) && ctype_digit($value) ? (int) $value : null;
    }
}
