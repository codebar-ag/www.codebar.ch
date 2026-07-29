<?php

declare(strict_types=1);

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
            'de_CH' => 'DE',
            'en_CH' => 'EN',
        ]);
})->group('unit', 'traits', 'labels');
