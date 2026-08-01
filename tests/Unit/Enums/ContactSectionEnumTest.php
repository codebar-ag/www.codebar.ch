<?php

declare(strict_types=1);

use App\Enums\ContactSectionEnum;

it('exposes the contact section keys', function () {
    expect(ContactSectionEnum::EMPLOYEES->value)->toBe('employees');
    expect(ContactSectionEnum::COLLABORATIONS->value)->toBe('collaborations');
    expect(ContactSectionEnum::BOARD_MEMBERS->value)->toBe('board_members');
})->group('unit', 'enums');

it('resolves a section from its stored key', function () {
    expect(ContactSectionEnum::tryFrom('board_members'))->toBe(ContactSectionEnum::BOARD_MEMBERS);
    expect(ContactSectionEnum::tryFrom('nope'))->toBeNull();
})->group('unit', 'enums');
