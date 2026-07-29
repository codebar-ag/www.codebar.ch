<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

/**
 * The cache keys the application writes by hand.
 *
 * They live here rather than as inline strings because a key is written in one place
 * and forgotten in another — a listing action and an observer — and a typo in either
 * half is invisible: the write succeeds, the invalidation silently misses, and the
 * site serves stale content until someone clears the cache by hand.
 *
 * Cases whose data is stored per language are read through forLocale(); the rest use
 * ->value directly.
 */
enum CacheKeyEnum: string
{
    const string VALID_FILESYSTEMS_DEFAULT = 'valid_filesystems_defaullt';

    case CONTACTS_PUBLISHED = 'contacts_published';

    case NEWS_PUBLISHED = 'news_published';

    case PRODUCTS_PUBLISHED = 'products_published';

    case SERVICES_PUBLISHED = 'services_published';

    case TECHNOLOGIES_PUBLISHED = 'technologies_published';

    case OPEN_SOURCE_PUBLISHED = 'open_source_published';

    case AI_MODELS_ACTIVE = 'ai_models_active';

    case AI_MODELS_ARCHIVED = 'ai_models_archived';

    case SITEMAP = 'sitemap_xml';

    public function forLocale(string $locale): string
    {
        return Str::slug("{$this->value}_{$locale}");
    }

    /**
     * @return array<int, string>
     */
    public function forAllLocales(): array
    {
        return array_map(
            fn (LocaleEnum $locale): string => $this->forLocale($locale->value),
            LocaleEnum::cases()
        );
    }
}
