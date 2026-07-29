<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Service;

/**
 * Reads the service files under database/files/services/{locale}/ and writes them to the
 * database. The markdown files are the source of truth — running this repeatedly is safe.
 */
class ImportServicesCommand extends ImportCommand
{
    protected $signature = 'services:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/services}';

    protected $description = 'Import services from database/files/services/{locale}/*.md';

    public function handle(): int
    {
        $documents = $this->readLocaleDocuments(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases())
        );

        if ($documents === []) {
            $this->components->warn('No service files found under '.$this->basePath().'.');

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
                    '"%s" is missing a translation for %s — every service must exist in both languages.',
                    $key,
                    implode(', ', $missing)
                ));
                $skipped++;

                continue;
            }

            $keys[] = $key;

            if ($dryRun) {
                $exists = Service::where('slug', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            $this->store($key, $localeDocuments);
            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans(Service::query(), 'slug', $keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d service(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/services';
    }

    /**
     * Shared fields (order, group, published, image, url, tags) are read from the
     * German file — a service is one thing with one set of metadata, translated
     * into two bodies of copy, not two independent records that happen to share a slug.
     *
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     */
    private function store(string $key, array $localeDocuments): void
    {
        $primary = $localeDocuments[LocaleEnum::DE->value]['front'];

        $name = [];
        $teaser = [];
        $content = [];

        foreach ($localeDocuments as $locale => $document) {
            $name[$locale] = $this->string($document['front']['name'] ?? '');
            $teaser[$locale] = $this->string($document['front']['teaser'] ?? '');
            $content[$locale] = $document['body'];
        }

        Service::updateOrCreate(
            ['slug' => $key],
            [
                'published' => (bool) ($primary['published'] ?? true),
                'group' => $this->string($primary['group'] ?? '') ?: 'services',
                'order' => $this->nullableInt($primary['order'] ?? null) ?? 0,
                'name' => $name,
                'teaser' => $teaser,
                'content' => $content,
                'image' => $this->string($primary['image'] ?? ''),
                'url' => $this->nullableString($primary['url'] ?? null),
                'tags' => $this->stringList($primary['tags'] ?? []),
            ]
        );
    }
}
