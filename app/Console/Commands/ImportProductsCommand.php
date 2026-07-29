<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Product;

/**
 * Reads the product files under database/files/products/{locale}/ and writes them to
 * the database. The markdown files are the source of truth — running this repeatedly
 * is safe.
 */
class ImportProductsCommand extends ImportCommand
{
    protected $signature = 'products:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/products}';

    protected $description = 'Import products from database/files/products/{locale}/*.md';

    public function handle(): int
    {
        $documents = $this->readLocaleDocuments(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases())
        );

        if ($documents === []) {
            $this->components->warn('No product files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = $this->isDryRun();
        $imported = 0;
        $skipped = 0;
        $keys = [];

        foreach ($documents as $key => $localeDocuments) {
            $missing = $this->missingLocales(
                array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
                $localeDocuments
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
            $this->removeOrphans(Product::query(), 'slug', $keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d product(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/products';
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
                'tags' => $this->stringList($primary['tags'] ?? []),
                ...$translated,
            ]
        );
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
}
