<?php

declare(strict_types=1);

use App\Actions\ViewDataAction;
use App\Enums\AiModelCategoryEnum;
use App\Enums\CacheKeyEnum;
use App\Models\AiModel;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Support\Facades\Cache;

/**
 * The listings are cached with rememberForever, so nothing expires them on its own.
 * If the observer stops firing, a content import writes to the database and the site
 * keeps serving the previous list — silently, and for good.
 */
it('drops the cached service listing when a service is saved', function () {
    Service::factory()->create(['published' => true, 'name' => ['de_CH' => 'Alt', 'en_CH' => 'Old']]);

    $before = (new ViewDataAction)->services('de_CH');
    expect(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('de_CH')))->toBeTrue()
        ->and($before)->toHaveCount(1);

    Service::factory()->create(['published' => true, 'name' => ['de_CH' => 'Neu', 'en_CH' => 'New']]);

    expect(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('de_CH')))->toBeFalse()
        ->and((new ViewDataAction)->services('de_CH'))->toHaveCount(2);
})->group('feature', 'observers');

it('drops the cached service listing when a service is deleted', function () {
    $service = Service::factory()->create(['published' => true]);

    (new ViewDataAction)->services('de_CH');
    expect(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('de_CH')))->toBeTrue();

    $service->delete();

    expect(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('de_CH')))->toBeFalse()
        ->and((new ViewDataAction)->services('de_CH'))->toHaveCount(0);
})->group('feature', 'observers');

it('drops every locale of a cached listing, not just the one that was read', function () {
    Service::factory()->create(['published' => true]);

    (new ViewDataAction)->services('de_CH');
    (new ViewDataAction)->services('en_CH');

    Service::factory()->create(['published' => true]);

    expect(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('de_CH')))->toBeFalse()
        ->and(Cache::has(CacheKeyEnum::SERVICES_PUBLISHED->forLocale('en_CH')))->toBeFalse();
})->group('feature', 'observers');

it('drops both AI model catalogues when a model changes', function () {
    AiModel::query()->create(['name' => 'gpt-x', 'category' => AiModelCategoryEnum::REASONING_CODING, 'order' => 1]);

    $viewData = new ViewDataAction;
    $viewData->aiModelGroups();
    $viewData->aiModelArchive();

    expect(Cache::has(CacheKeyEnum::AI_MODELS_ACTIVE->value))->toBeTrue()
        ->and(Cache::has(CacheKeyEnum::AI_MODELS_ARCHIVED->value))->toBeTrue();

    AiModel::query()->create(['name' => 'gpt-y', 'category' => AiModelCategoryEnum::REASONING_CODING, 'order' => 2]);

    expect(Cache::has(CacheKeyEnum::AI_MODELS_ACTIVE->value))->toBeFalse()
        ->and(Cache::has(CacheKeyEnum::AI_MODELS_ARCHIVED->value))->toBeFalse();
})->group('feature', 'observers');

it('drops the cached listing for every observed content type', function (Closure $create, CacheKeyEnum $key) {
    $create();

    Cache::put($key->forLocale('de_CH'), 'stale', now()->addYear());

    $create();

    expect(Cache::has($key->forLocale('de_CH')))->toBeFalse();
})->with([
    'products' => [fn () => Product::factory()->create(['published' => true]), CacheKeyEnum::PRODUCTS_PUBLISHED],
    'technologies' => [fn () => Technology::factory()->create(['published' => true]), CacheKeyEnum::TECHNOLOGIES_PUBLISHED],
    'open source' => [fn () => OpenSource::factory()->create(['published' => true]), CacheKeyEnum::OPEN_SOURCE_PUBLISHED],
])->group('feature', 'observers');
