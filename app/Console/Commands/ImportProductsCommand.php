<?php

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Product;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads the product files under database/files/products/{locale}/ and writes them to
 * the database. The markdown files are the source of truth — running this repeatedly
 * is safe.
 */
class ImportProductsCommand extends Command
{
    protected $signature = 'products:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/products}';

    protected $description = 'Import products from database/files/products/{locale}/*.md';

    public function handle(): int
    {
        $documents = $this->readDocuments();

        if ($documents === []) {
            $this->components->warn('No product files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $imported = 0;
        $skipped = 0;
        $keys = [];

        foreach ($documents as $key => $localeDocuments) {
            $missing = array_diff(
                array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
                array_keys($localeDocuments)
            );

            if ($missing !== []) {
                $this->components->error(sprintf(
                    '"%s" is missing a translation for %s — every product must exist in both languages.',
                    $key,
                    implode(', ', $missing)
                ));
                $skipped++;

                continue;
            }

            $keys[] = $key;

            if ($dryRun) {
                $exists = Product::where('slug', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            $this->store($key, $localeDocuments);
            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans($keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d product(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function basePath(): string
    {
        $override = $this->option('path');

        return is_string($override) && $override !== '' ? rtrim($override, '/') : database_path('files/products');
    }

    /**
     * @return array<string, array<string, array{front: array<string, mixed>, body: string}>>
     */
    private function readDocuments(): array
    {
        $documents = [];

        foreach (LocaleEnum::cases() as $case) {
            $directory = $this->basePath().'/'.$case->value;

            if (! is_dir($directory)) {
                continue;
            }

            foreach (glob($directory.'/*.md') ?: [] as $path) {
                $parsed = $this->parseFile($path);

                if ($parsed === null) {
                    continue;
                }

                $key = $parsed['front']['key'] ?? null;

                if (! is_string($key) || $key === '') {
                    $this->components->error(basename($path).' has no "key" in its front matter — skipped.');

                    continue;
                }

                $expected = $key.'.md';

                if (basename($path) !== $expected) {
                    $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
                }

                $documents[$key][$case->value] = $parsed;
            }
        }

        ksort($documents);

        return $documents;
    }

    /**
     * @return array{front: array<string, mixed>, body: string}|null
     */
    private function parseFile(string $path): ?array
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
            $front = Yaml::parse($parts[1]);
        } catch (ParseException $exception) {
            $this->components->error(basename($path).': '.$exception->getMessage());

            return null;
        }

        $normalised = [];

        if (is_array($front)) {
            foreach ($front as $field => $value) {
                if (is_string($field)) {
                    $normalised[$field] = $value;
                }
            }
        }

        return [
            'front' => $normalised,
            'body' => trim($parts[2]),
        ];
    }

    /**
     * Shared fields (order, image, url, tags) are read from the German file — a
     * product is one thing with one set of metadata, translated into two bodies of
     * copy, not two independent records that happen to share a slug.
     *
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     */
    private function store(string $key, array $localeDocuments): void
    {
        $primary = $localeDocuments[LocaleEnum::DE->value]['front'];

        $translated = ['name' => [], 'headline' => [], 'teaser' => [], 'content' => []];
        $translated += ['features_heading' => [], 'features_intro' => [], 'features' => []];
        $translated += ['deployment_heading' => [], 'deployment_intro' => [], 'deployment_options' => []];
        $translated += ['cta_heading' => [], 'cta_body' => []];

        foreach ($localeDocuments as $locale => $document) {
            $front = $document['front'];

            $translated['name'][$locale] = $this->string($front['name'] ?? '');
            $translated['headline'][$locale] = $this->string($front['headline'] ?? '');
            $translated['teaser'][$locale] = $this->string($front['teaser'] ?? '');
            $translated['content'][$locale] = $document['body'];
            $translated['features_heading'][$locale] = $this->string($front['features_heading'] ?? '');
            $translated['features_intro'][$locale] = $this->string($front['features_intro'] ?? '');
            $translated['features'][$locale] = $this->items($front['features'] ?? []);
            $translated['deployment_heading'][$locale] = $this->string($front['deployment_heading'] ?? '');
            $translated['deployment_intro'][$locale] = $this->string($front['deployment_intro'] ?? '');
            $translated['deployment_options'][$locale] = $this->items($front['deployment_options'] ?? []);
            $translated['cta_heading'][$locale] = $this->string($front['cta_heading'] ?? '');
            $translated['cta_body'][$locale] = $this->string($front['cta_body'] ?? '');
        }

        Product::updateOrCreate(
            ['slug' => $key],
            [
                'published' => (bool) ($primary['published'] ?? true),
                'order' => $this->nullableInt($primary['order'] ?? null) ?? 0,
                'image' => $this->string($primary['image'] ?? ''),
                'url' => $this->nullableString($primary['url'] ?? null),
                'tags' => $this->tagLabels($primary['tags'] ?? []),
                ...$translated,
            ]
        );
    }

    /**
     * A product removed from the repository must disappear from the site too,
     * otherwise the files stop being the source of truth.
     *
     * @param  array<int, string>  $keys
     */
    private function removeOrphans(array $keys): void
    {
        $orphans = Product::whereNotIn('slug', $keys)->get();

        foreach ($orphans as $orphan) {
            $this->components->twoColumnDetail($orphan->slug, '<fg=red>removed</>');
            $orphan->delete();
        }
    }

    /**
     * Normalises a `features`/`deployment_options` front-matter list into
     * {title, description} pairs, dropping anything malformed.
     *
     * @return array<int, array{title: string, description: string}>
     */
    private function items(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $title = $this->string($item['title'] ?? '');
            $description = $this->string($item['description'] ?? '');

            if ($title === '' && $description === '') {
                continue;
            }

            $result[] = ['title' => $title, 'description' => $description];
        }

        return $result;
    }

    /**
     * @return array<int, string>
     */
    private function tagLabels(mixed $tags): array
    {
        if (! is_array($tags)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $tag): string => $this->string($tag),
            $tags
        ), fn (string $tag): bool => $tag !== ''));
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
