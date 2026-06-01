<?php

use App\Enums\LocaleEnum;

it('exposes the expected cases', function () {
    expect(LocaleEnum::cases())->toBe([
        LocaleEnum::DE,
        LocaleEnum::EN,
    ]);
})->group('enums', 'locale-enum');

it('returns labels', function () {
    expect(LocaleEnum::DE->label())->toBe('Deutsch');
    expect(LocaleEnum::EN->label())->toBe('English');
})->group('enums', 'locale-enum');
