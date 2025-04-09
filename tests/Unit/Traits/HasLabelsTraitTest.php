<?php

use App\Enums\LocaleEnum;

it('has enum labels', function () {
    $enumLabels = collect(LocaleEnum::cases())
        ->flatMap(function (LocaleEnum $enum) {
            return [$enum->value => $enum->getLabel()];
        })->toArray();

    expect($enumLabels)
        ->toBeArray()
        ->toHaveCount(2)
        ->toBe([
            'de_CH' => 'German',
            'en_CH' => 'English',
        ]);
})->group('unit', 'traits', 'labels');
