<?php

use App\Enums\LocaleEnum;

it('returns correct labels array', function () {
    expect(LocaleEnum::DE->getLabel())->toBe(__('German'))
        ->and(LocaleEnum::EN->getLabel())->toBe(__('English'));
})->group('enums', 'locale-enum');

it('returns correct cases array', function () {

    $expectedCases = [
        LocaleEnum::DE,
        LocaleEnum::EN,
    ];

    expect(LocaleEnum::cases())->toBe($expectedCases);

})->group('enums', 'locale-enum');
