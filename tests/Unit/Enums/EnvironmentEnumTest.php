<?php

use App\Enums\EnvironmentEnum;

it('exposes the expected cases', function () {
    expect(EnvironmentEnum::cases())->toBe([
        EnvironmentEnum::LOCAL,
        EnvironmentEnum::STAGING,
        EnvironmentEnum::PRODUCTION,
    ]);
})->group('enums', 'environment-enum');
