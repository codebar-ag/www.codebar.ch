<?php

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

dataset('routes', function () {
    return [
        // EN-CH
        [LocaleEnum::EN->value, 'start.index'],
        [LocaleEnum::EN->value, 'news.index'],
        //		[LocaleEnum::EN->value, 'news.show', ['news' => 1]],
        [LocaleEnum::EN->value, 'about-us.index'],
        [LocaleEnum::EN->value, 'services.index'],
        //		[LocaleEnum::EN->value, 'services.show', ['service' => 1]],
        [LocaleEnum::EN->value, 'products.index'],
        //		[LocaleEnum::EN->value, 'products.show', ['product' => 1]],
        [LocaleEnum::EN->value, 'legal.imprint.index'],
        [LocaleEnum::EN->value, 'legal.privacy.index'],
        [LocaleEnum::EN->value, 'media.index'],
        [LocaleEnum::EN->value, 'contact.index'],
        [LocaleEnum::EN->value, 'ai.index'],
        [LocaleEnum::EN->value, 'ai.llm.index'],
        [LocaleEnum::EN->value, 'ai.llm.analytics.index'],

        // DE-CH
        [LocaleEnum::DE->value, 'start.index'],
        [LocaleEnum::DE->value, 'news.index'],
        //		[LocaleEnum::DE->value, 'news.show', ['news' => 1]],
        [LocaleEnum::DE->value, 'services.index'],
        //		[LocaleEnum::DE->value, 'services.show', ['service' => 1]],
        [LocaleEnum::DE->value, 'products.index'],
        //		[LocaleEnum::DE->value, 'products.show', ['product' => 1]],
        [LocaleEnum::DE->value, 'legal.imprint.index'],
        [LocaleEnum::DE->value, 'legal.privacy.index'],
        [LocaleEnum::DE->value, 'media.index'],
        [LocaleEnum::DE->value, 'contact.index'],
        [LocaleEnum::DE->value, 'ai.index'],
        [LocaleEnum::DE->value, 'ai.llm.index'],
        [LocaleEnum::DE->value, 'ai.llm.analytics.index'],
    ];
});

it('returns 200 for localized route', function (string $locale, string $name, array $params = []) {
    $route = route(Str::slug($locale).'.'.$name, $params);

    get($route)->assertOk();
})->with('routes')->group('routes');
