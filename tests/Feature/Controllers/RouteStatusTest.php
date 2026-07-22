<?php

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

dataset('routes', function () {
    return [
        // EN-CH
        [LocaleEnum::EN->value, 'start.index'],
        [LocaleEnum::EN->value, 'about-us.index'],
        [LocaleEnum::EN->value, 'legal.imprint.index'],
        [LocaleEnum::EN->value, 'legal.privacy.index'],
        [LocaleEnum::EN->value, 'media.index'],
        [LocaleEnum::EN->value, 'contact.index'],
        [LocaleEnum::EN->value, 'ai.index'],
        [LocaleEnum::EN->value, 'ai.llm.index'],
        [LocaleEnum::EN->value, 'ai.llm.analytics.index'],
        [LocaleEnum::EN->value, 'network.index'],
        [LocaleEnum::EN->value, 'network.request.index'],
        [LocaleEnum::EN->value, 'services.index'],

        // DE-CH
        [LocaleEnum::DE->value, 'start.index'],
        [LocaleEnum::DE->value, 'about-us.index'],
        [LocaleEnum::DE->value, 'legal.imprint.index'],
        [LocaleEnum::DE->value, 'legal.privacy.index'],
        [LocaleEnum::DE->value, 'media.index'],
        [LocaleEnum::DE->value, 'contact.index'],
        [LocaleEnum::DE->value, 'ai.index'],
        [LocaleEnum::DE->value, 'ai.llm.index'],
        [LocaleEnum::DE->value, 'ai.llm.analytics.index'],
        [LocaleEnum::DE->value, 'network.index'],
        [LocaleEnum::DE->value, 'network.request.index'],
        [LocaleEnum::DE->value, 'services.index'],
    ];
});

it('returns 200 for localized route', function (string $locale, string $name, array $params = []) {
    $route = route(Str::slug($locale).'.'.$name, $params);

    get($route)->assertOk();
})->with('routes')->group('routes');

dataset('disabled-routes', function () {
    return [
        [LocaleEnum::EN->value, 'news.index'],
        [LocaleEnum::EN->value, 'products.index'],
        [LocaleEnum::EN->value, 'technologies.index'],
        [LocaleEnum::EN->value, 'open-source.index'],
        [LocaleEnum::EN->value, 'jobs.index'],
        [LocaleEnum::EN->value, 'legal.terms.index'],
        [LocaleEnum::DE->value, 'news.index'],
        [LocaleEnum::DE->value, 'products.index'],
        [LocaleEnum::DE->value, 'technologies.index'],
        [LocaleEnum::DE->value, 'open-source.index'],
        [LocaleEnum::DE->value, 'jobs.index'],
        [LocaleEnum::DE->value, 'legal.terms.index'],
    ];
});

it('redirects disabled routes to the default locale start page', function (string $locale, string $name) {
    $route = route(Str::slug($locale).'.'.$name);

    get($route)->assertRedirect(route(Str::slug(LocaleEnum::DE->value).'.start.index'));
})->with('disabled-routes')->group('routes');
