<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\CacheKeyEnum;
use App\Models\News;
use App\Support\ResponseCacheFlusher;
use Illuminate\Support\Facades\Cache;

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
        foreach (CacheKeyEnum::NEWS_PUBLISHED->forAllLocales() as $key) {
            Cache::forget($key);
        }

        ResponseCacheFlusher::flush();
    }
}
