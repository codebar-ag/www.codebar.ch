<?php

declare(strict_types=1);

use App\Enums\RoleEnum;

it('returns correct labels array', function () {
    expect(RoleEnum::ADMINISTRATOR->getLabel())->toBe(__('enums.role.administrator'))
        ->and(RoleEnum::USER->getLabel())->toBe(__('enums.role.user'))
        ->and(RoleEnum::API->getLabel())->toBe(__('enums.role.api'));
})->group('enums', 'role-enum');

it('returns correct cases array', function () {

    $expectedCases = [
        RoleEnum::ADMINISTRATOR,
        RoleEnum::USER,
        RoleEnum::API,
    ];

    expect(RoleEnum::cases())->toBe($expectedCases);

})->group('enums', 'role-enum');
