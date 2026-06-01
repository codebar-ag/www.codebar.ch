<?php

namespace App\Content;

use App\Enums\LocaleEnum;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class ContentItem implements \Stringable, UrlRoutable
{
    public function __construct(
        public readonly string $type,
        public readonly LocaleEnum $locale,
        public readonly string $slug,
        public readonly string $title,
        public readonly ?string $teaser,
        public readonly ?string $image,
        public readonly string $body,
        public readonly array $frontmatter,
        public readonly ?Carbon $publishedAt,
        public readonly int $order,
    ) {}

    public function isPublished(): bool
    {
        return (bool) ($this->frontmatter['published'] ?? true);
    }

    public function tags(): Collection
    {
        return collect($this->frontmatter['tags'] ?? []);
    }

    public function __get(string $name): mixed
    {
        return match ($name) {
            'name' => $this->title,
            'content' => $this->body,
            'tags' => $this->tags(),
            'updated_at', 'published_at' => $this->publishedAt,
            default => $this->frontmatter[$name] ?? null,
        };
    }

    public function __isset(string $name): bool
    {
        if (in_array($name, ['name', 'content', 'tags', 'updated_at', 'published_at'], true)) {
            return true;
        }

        return array_key_exists($name, $this->frontmatter);
    }

    public function getRouteKey(): string
    {
        return $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return null;
    }

    public function resolveChildRouteBinding($childType, $value, $field): ?self
    {
        return null;
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
