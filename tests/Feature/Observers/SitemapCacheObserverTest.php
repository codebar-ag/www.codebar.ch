<?php

declare(strict_types=1);

use App\Enums\CacheKeyEnum;
use App\Models\News;
use App\Models\OpenSource;
use Illuminate\Support\Facades\Cache;

it('drops the cached sitemap when observed content is saved', function (Closure $create) {
    Cache::put(CacheKeyEnum::SITEMAP->value, 'stale', now()->addDay());

    $create();

    expect(Cache::has(CacheKeyEnum::SITEMAP->value))->toBeFalse();
})->with([
    'news' => [fn () => News::factory()->create()],
    'open source' => [fn () => OpenSource::factory()->create()],
])->group('feature', 'observers');

it('drops the cached sitemap when observed content is deleted', function () {
    $news = News::factory()->create();

    Cache::put(CacheKeyEnum::SITEMAP->value, 'stale', now()->addDay());

    $news->delete();

    expect(Cache::has(CacheKeyEnum::SITEMAP->value))->toBeFalse();
})->group('feature', 'observers');
