<?php

declare(strict_types=1);

use App\Enums\CacheKeyEnum;
use App\Enums\LocaleEnum;

it('builds a slugged per-locale cache key', function () {
    expect(CacheKeyEnum::NEWS_PUBLISHED->forLocale('de_CH'))->toBe('news-published-de-ch');
})->group('unit', 'enums');

it('builds one key per configured locale', function () {
    $keys = CacheKeyEnum::CONTACTS_PUBLISHED->forAllLocales();

    expect($keys)->toBe(array_map(
        fn (LocaleEnum $locale): string => CacheKeyEnum::CONTACTS_PUBLISHED->forLocale($locale->value),
        LocaleEnum::cases(),
    ));
})->group('unit', 'enums');
