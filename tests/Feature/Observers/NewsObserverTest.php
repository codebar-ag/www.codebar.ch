<?php

declare(strict_types=1);

use App\Enums\CacheKeyEnum;
use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

it('drops the published-news cache for every locale when an article is saved', function () {
    $news = News::factory()->create();

    foreach (CacheKeyEnum::NEWS_PUBLISHED->forAllLocales() as $key) {
        Cache::put($key, 'stale', now()->addYear());
    }

    $news->update(['published' => false]);

    foreach (CacheKeyEnum::NEWS_PUBLISHED->forAllLocales() as $key) {
        expect(Cache::has($key))->toBeFalse();
    }
})->group('feature', 'observers');

it('drops the published-news cache for every locale when an article is deleted', function () {
    $news = News::factory()->create();

    foreach (CacheKeyEnum::NEWS_PUBLISHED->forAllLocales() as $key) {
        Cache::put($key, 'stale', now()->addYear());
    }

    $news->delete();

    foreach (CacheKeyEnum::NEWS_PUBLISHED->forAllLocales() as $key) {
        expect(Cache::has($key))->toBeFalse();
    }
})->group('feature', 'observers');

it('flushes the rendered pages when an article is saved', function () {
    ResponseCache::shouldReceive('clear')->once();

    News::factory()->create();
})->group('feature', 'observers');

it('flushes the rendered pages when an article is deleted', function () {
    $news = News::factory()->create();

    ResponseCache::shouldReceive('clear')->once();

    $news->delete();
})->group('feature', 'observers');
