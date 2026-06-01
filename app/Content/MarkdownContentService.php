<?php

namespace App\Content;

use App\Enums\LocaleEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\CommonMarkConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class MarkdownContentService
{
    public function __construct(
        private readonly string $basePath,
        private readonly int $cacheTtl,
    ) {}

    /** @return Collection<int, ContentItem> */
    public function all(string $type, LocaleEnum $locale): Collection
    {
        $build = fn (): Collection => $this->load($type, $locale)
            ->filter(fn (ContentItem $item) => $item->isPublished())
            ->sortBy([
                ['order', 'asc'],
                ['publishedAt', 'desc'],
            ])
            ->values();

        if ($this->cacheTtl <= 0) {
            return $build();
        }

        return Cache::remember(
            $this->cacheKey('all', $type, $locale->value),
            $this->cacheTtl,
            $build,
        );
    }

    public function find(string $type, LocaleEnum $locale, string $slug): ?ContentItem
    {
        return $this->all($type, $locale)
            ->first(fn (ContentItem $item) => $item->slug === $slug);
    }

    public function flush(): void
    {
        Cache::flush();
    }

    /** @return Collection<int, ContentItem> */
    private function load(string $type, LocaleEnum $locale): Collection
    {
        $directory = sprintf('%s/%s/%s', rtrim($this->basePath, '/'), $type, $locale->value);

        if (! is_dir($directory)) {
            return collect();
        }

        $converter = new CommonMarkConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        return collect(glob($directory.'/*.md') ?: [])
            ->map(function (string $path) use ($type, $locale, $converter): ContentItem {
                $document = YamlFrontMatter::parseFile($path);
                $matter = $document->matter();
                $slug = (string) ($matter['slug'] ?? pathinfo($path, PATHINFO_FILENAME));
                $publishedAt = match (true) {
                    ! isset($matter['publishedAt']) => null,
                    is_int($matter['publishedAt']) => Carbon::createFromTimestamp($matter['publishedAt']),
                    $matter['publishedAt'] instanceof \DateTimeInterface => Carbon::instance($matter['publishedAt']),
                    default => Carbon::parse((string) $matter['publishedAt']),
                };

                return new ContentItem(
                    type: $type,
                    locale: $locale,
                    slug: $slug,
                    title: (string) ($matter['title'] ?? $slug),
                    teaser: isset($matter['teaser']) ? (string) $matter['teaser'] : null,
                    image: isset($matter['image']) ? (string) $matter['image'] : null,
                    body: (string) $converter->convert($document->body()),
                    frontmatter: $matter,
                    publishedAt: $publishedAt,
                    order: (int) ($matter['order'] ?? PHP_INT_MAX),
                );
            });
    }

    private function cacheKey(string ...$parts): string
    {
        return 'content:'.implode(':', $parts);
    }
}
