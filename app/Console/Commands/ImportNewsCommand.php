<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Markdown\NewsMarkdown;
use App\Models\Contact;
use App\Models\News;
use App\Models\NewsSeries;
use App\Models\NewsTag;
use App\Observers\NewsObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Reads the article files under database/files/news/{locale}/ and writes them to the
 * database. The markdown files are the source of truth — running this repeatedly is safe.
 */
class ImportNewsCommand extends ImportCommand
{
    protected $signature = 'news:import
                            {--dry-run : Show what would change without writing anything}
                            {--key= : Import a single article by its key}
                            {--path= : Read from this directory instead of database/files/news}';

    protected $description = 'Import news articles from database/files/news/{locale}/*.md';

    public function handle(NewsMarkdown $markdown): int
    {
        // PARSE_DATETIME: without it a bare `published_at: 2026-07-28` arrives as a Unix timestamp.
        $documents = $this->readLocaleDocuments(
            array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
            parseDateTime: true,
        );

        if ($documents === []) {
            $this->components->warn('No article files found under '.$this->basePath().'.');

            return self::SUCCESS;
        }

        $dryRun = $this->isDryRun();
        $only = $this->option('key');

        $imported = 0;
        $skipped = 0;

        foreach ($documents as $key => $localeDocuments) {
            if (is_string($only) && $only !== '' && $only !== $key) {
                continue;
            }

            $missing = $this->missingLocales(
                array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases()),
                $localeDocuments
            );

            if ($missing !== []) {
                $this->components->error(sprintf(
                    '"%s" is missing a translation for %s — every article must exist in both languages.',
                    $key,
                    implode(', ', $missing)
                ));
                $skipped++;

                continue;
            }

            if ($dryRun) {
                $existing = News::where('key', $key)->exists();
                $this->components->twoColumnDetail($key, $existing ? '<fg=yellow>would update</>' : '<fg=green>would create</>');
                $imported++;

                continue;
            }

            $this->store($key, $localeDocuments, $markdown);
            $this->components->twoColumnDetail($key, '<fg=green>imported</>');
            $imported++;
        }

        if (! $dryRun && $imported > 0) {
            $this->linkRelatedArticles($documents);
            NewsObserver::flush();
        }

        $this->newLine();
        $this->components->info(sprintf('%d article(s) %s, %d skipped.', $imported, $dryRun ? 'pending' : 'imported', $skipped));

        return $skipped > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function defaultPath(): string
    {
        return 'files/news';
    }

    /**
     * Warns when a file breaks the naming convention. Both language files of an article
     * carry the same name, so a listing sorts by publication date and the pair sits
     * together. A mismatch is reported, not fatal — renaming must never break an import.
     *
     * @param  array<string, mixed>  $front
     */
    protected function checkFileName(string $path, string $key, array $front): void
    {
        $publishedAt = $this->publishedAt($front['published_at'] ?? null);

        if ($publishedAt === null) {
            return;
        }

        $expected = $publishedAt->format('Y-m-d').'-'.$key.'.md';

        if (basename($path) !== $expected) {
            $this->components->warn(sprintf('%s should be named %s.', basename($path), $expected));
        }
    }

    /**
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     */
    private function store(string $key, array $localeDocuments, NewsMarkdown $markdown): void
    {
        $primary = $localeDocuments[LocaleEnum::DE->value]['front'];

        $translated = ['title' => [], 'teaser' => [], 'content' => [], 'slug' => []];
        $optional = ['hero_caption' => [], 'hero_alt' => []];

        foreach ($localeDocuments as $locale => $document) {
            $translated['title'][$locale] = $this->string($document['front']['title'] ?? '');
            $translated['teaser'][$locale] = $this->string($document['front']['teaser'] ?? '');
            $translated['content'][$locale] = $document['body'];
            $translated['slug'][$locale] = Str::slug($this->string(
                $document['front']['slug'] ?? $document['front']['title'] ?? $key
            ));
            $optional['hero_caption'][$locale] = $this->string($document['front']['hero_caption'] ?? '');
            $optional['hero_alt'][$locale] = $this->string($document['front']['hero_alt'] ?? '');
        }

        // Languages without a caption are left out rather than stored as empty strings.
        foreach ($optional as $field => $values) {
            $translated[$field] = array_filter($values);
        }

        $news = $this->existing($key, $translated['slug']) ?? new News(['key' => $key]);

        $news->fill([
            ...$translated,
            'hero_image' => $this->nullableString($primary['hero'] ?? null),
            'published_at' => $this->publishedAt($primary['published_at'] ?? null),
            // Defaults to true: an article with a date is live unless it says otherwise.
            'published' => (bool) ($primary['published'] ?? true),
            'author' => $this->nullableString($primary['author_name'] ?? null),
            'contact_id' => $this->resolveContactId($primary['author'] ?? null),
            'series_id' => $this->resolveSeriesId($primary['series'] ?? null, $localeDocuments),
            'series_position' => $this->nullableInt($primary['series_position'] ?? null),
            'featured' => (bool) ($primary['featured'] ?? false),
            'tags' => $this->stringList($primary['tags'] ?? []),
            'reading_minutes' => $markdown->readingMinutes($localeDocuments[LocaleEnum::DE->value]['body']),
        ]);

        $news->key = $key;
        $news->save();

        $news->newsTags()->sync($this->resolveTagIds($primary['tags'] ?? [], $localeDocuments));
    }

    /**
     * Matches on the key first, then on any of the slugs. The fallback lets an article
     * be re-keyed without colliding with its own row on the per-locale slug index.
     *
     * @param  array<string, string>  $slugs
     */
    private function existing(string $key, array $slugs): ?News
    {
        $news = News::where('key', $key)->first();

        if ($news !== null) {
            return $news;
        }

        return News::where(function ($query) use ($slugs): void {
            foreach ($slugs as $locale => $slug) {
                $query->orWhere('slug->'.$locale, $slug);
            }
        })->first();
    }

    private function publishedAt(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_int($value)) {
            return Carbon::createFromTimestamp($value);
        }

        $date = $this->string($value);

        return $date === '' ? null : Carbon::parse($date);
    }

    /**
     * The author is a contacts row: either its numeric id or the email in its icons.
     */
    private function resolveContactId(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $id = $this->nullableInt($value);

        if ($id !== null) {
            return Contact::whereKey($id)->exists() ? $id : null;
        }

        $needle = mb_strtolower($this->string($value));

        $match = Contact::all()->first(function (Contact $contact) use ($needle): bool {
            $icons = $contact->icons;
            $email = is_array($icons) && isset($icons['email']) ? $this->string($icons['email']) : '';

            return mb_strtolower($email) === $needle || mb_strtolower($contact->name) === $needle;
        });

        if ($match === null) {
            $this->components->warn(sprintf(
                'No contact found for author "%s" — the article keeps its plain author name.',
                $this->string($value)
            ));

            return null;
        }

        return $match->id;
    }

    /**
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     */
    private function resolveSeriesId(mixed $value, array $localeDocuments): ?int
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        $titles = [];
        $slugs = [];

        foreach ($localeDocuments as $locale => $document) {
            $title = $this->string($document['front']['series_title'] ?? '') ?: Str::headline($value);
            $titles[$locale] = $title;
            $slugs[$locale] = Str::slug($this->string($document['front']['series_slug'] ?? '') ?: $title);
        }

        $series = NewsSeries::firstOrNew(['key' => $value]);

        // Only fill the labels on creation — an existing series may have been edited.
        if (! $series->exists) {
            $series->setTranslations('title', $titles);
            $series->setTranslations('slug', $slugs);
            $series->published = true;
            $series->save();
        }

        return $series->id;
    }

    /**
     * @param  array<string, array{front: array<string, mixed>, body: string}>  $localeDocuments
     * @return array<int, int>
     */
    private function resolveTagIds(mixed $tags, array $localeDocuments): array
    {
        $ids = [];

        foreach ($this->stringList($tags) as $label) {
            // "DMS/ECM" must not collapse into "dmsecm".
            $key = Str::slug(str_replace(['/', '&'], ' ', $label));
            $tag = NewsTag::firstOrNew(['key' => $key]);

            if (! $tag->exists) {
                $titles = [];
                $slugs = [];

                foreach (array_keys($localeDocuments) as $locale) {
                    // A tag written in the German file is used verbatim in both languages
                    // until someone gives it a translated label in the database.
                    $titles[$locale] = $label;
                    $slugs[$locale] = $key;
                }

                $tag->setTranslations('title', $titles);
                $tag->setTranslations('slug', $slugs);
                $tag->save();
            }

            $ids[] = $tag->id;
        }

        return $ids;
    }

    /**
     * @param  array<string, array<string, array{front: array<string, mixed>, body: string}>>  $documents
     */
    private function linkRelatedArticles(array $documents): void
    {
        foreach ($documents as $key => $localeDocuments) {
            $front = $localeDocuments[LocaleEnum::DE->value]['front'] ?? [];
            $related = $front['related'] ?? [];

            if (! is_array($related) || $related === []) {
                continue;
            }

            $news = News::where('key', $key)->first();

            if ($news === null) {
                continue;
            }

            $keys = array_values(array_map(fn (mixed $k): string => $this->string($k), $related));

            /** @var array<int, int> $targets */
            $targets = News::whereIn('key', $keys)->pluck('id')->all();

            $sync = [];

            foreach (array_values($targets) as $index => $id) {
                $sync[$id] = ['sort' => $index];
            }

            $news->relatedArticles()->sync($sync);
        }
    }
}
