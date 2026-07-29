<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\CacheKeyEnum;
use App\Models\AiModel;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

/**
 * Drops the cached listing of a content type whenever one of its rows changes.
 *
 * ViewDataAction caches these listings with rememberForever, and the rendered HTML is
 * cached on top of that. Without this, `services:import` wrote to the database and the
 * site kept serving the previous list — with no TTL, forever, until someone cleared the
 * cache by hand. Same shape as NewsObserver, which solved this for articles first.
 *
 * Query-builder updates bypass model events, so a command that writes through
 * `Model::whereKey(...)->update(...)` has to call flush() itself.
 */
class ContentCacheObserver
{
    public function saved(Model $model): void
    {
        self::flush($model);
    }

    public function deleted(Model $model): void
    {
        self::flush($model);
    }

    public static function flush(Model $model): void
    {
        foreach (self::keysFor($model) as $key) {
            Cache::forget($key);
        }

        ResponseCache::clear();
    }

    /**
     * @return array<int, string>
     */
    private static function keysFor(Model $model): array
    {
        return match (true) {
            $model instanceof Service => CacheKeyEnum::SERVICES_PUBLISHED->forAllLocales(),
            $model instanceof Product => CacheKeyEnum::PRODUCTS_PUBLISHED->forAllLocales(),
            $model instanceof Technology => CacheKeyEnum::TECHNOLOGIES_PUBLISHED->forAllLocales(),
            $model instanceof OpenSource => CacheKeyEnum::OPEN_SOURCE_PUBLISHED->forAllLocales(),
            $model instanceof AiModel => [
                CacheKeyEnum::AI_MODELS_ACTIVE->value,
                CacheKeyEnum::AI_MODELS_ARCHIVED->value,
            ],
            default => [],
        };
    }
}
