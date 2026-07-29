<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\Technology;

/**
 * Reads the technology files under database/files/technologies/{locale}/ and writes
 * them to the database. The markdown files are the source of truth — running this
 * repeatedly is safe.
 */
class ImportTechnologiesCommand extends ImportCommand
{
    protected $signature = 'technologies:import
                            {--dry-run : Show what would change without writing anything}
                            {--path= : Read from this directory instead of database/files/technologies}';

    protected $description = 'Import technologies from database/files/technologies/{locale}/*.md';

    public function handle(): int
    {
        $documents = $this->readLocaleDocuments(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases())
        );

        if ($documents === []) {
            $this->components->warn('No technology files found under '.$this->basePath().'.');

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
                    '"%s" is missing a translation for %s — every technology must exist in both languages.',
                    $key,
                    implode(', ', $missing)
                ));
                $skipped++;

                continue;
            }

            $keys[] = $key;

            if ($dryRun) {
                $exists = Technology::where('slug', $key)->exists();
                $this->components->twoColumnDetail($key, $exists ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            $this->store($key, $localeDocuments);
            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun) {
            $this->removeOrphans(Technology::query(), 'slug', $keys);
        }

        $this->newLine();
        $this->components->info(sprintf('%d technology/ies %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/technologies';
    }

    /**
     * Shared fields (group, order, published, image, link, tags) are read from the
     * German file — a technology is one thing translated into two bodies of copy,
     * not two independent records that happen to share a slug.
     *
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     */
    private function store(string $key, array $localeDocuments): void
    {
        $primary = $localeDocuments[LocaleEnum::DE->value]['front'];

        $title = [];
        $teaser = [];
        $content = [];

        foreach ($localeDocuments as $locale => $document) {
            $title[$locale] = $this->string($document['front']['title'] ?? '');
            $teaser[$locale] = $this->string($document['front']['teaser'] ?? '');
            $content[$locale] = $document['body'];
        }

        Technology::updateOrCreate(
            ['slug' => $key],
            [
                'published' => (bool) ($primary['published'] ?? true),
                'group' => $this->string($primary['group'] ?? ''),
                'order' => $this->nullableInt($primary['order'] ?? null) ?? 0,
                'title' => $title,
                'teaser' => $teaser,
                'content' => $content,
                'image' => $this->string($primary['image'] ?? ''),
                'link' => $this->nullableString($primary['link'] ?? null),
                'tags' => $this->stringList($primary['tags'] ?? []),
            ]
        );
    }
}
