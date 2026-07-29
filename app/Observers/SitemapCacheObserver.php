<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\CacheKeyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Drops the cached sitemap whenever content that appears in it changes.
 *
 * The sitemap is cached for 24h. Without this, a new article or a corrected
 * page title would take up to a day to show up for crawlers — and a stale
 * sitemap can advertise URLs that no longer resolve.
 */
class SitemapCacheObserver
{
    public function saved(Model $model): void
    {
        $this->forget();
    }

    public function deleted(Model $model): void
    {
        $this->forget();
    }

    private function forget(): void
    {
        Cache::forget(CacheKeyEnum::SITEMAP->value);
    }
}
