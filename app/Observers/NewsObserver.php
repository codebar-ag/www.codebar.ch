<?php

namespace App\Observers;

use App\Enums\LocaleEnum;
use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\ResponseCache\Facades\ResponseCache;

/**
 * The published-news list is cached forever per locale and the rendered HTML is cached
 * on top of that. Without this, a freshly imported article appeared in the sitemap
 * immediately but stayed invisible on /aktuelles until someone cleared the cache by hand.
 */
class NewsObserver
{
    public function saved(News $news): void
    {
        self::flush();
    }

    public function deleted(News $news): void
    {
        self::flush();
    }

    public static function flush(): void
    {
        foreach (LocaleEnum::cases() as $locale) {
            Cache::forget(Str::slug("news_published_{$locale->value}"));
        }

        ResponseCache::clear();
    }
}
