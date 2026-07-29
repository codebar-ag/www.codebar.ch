<?php

declare(strict_types=1);

use App\Enums\ContactSectionEnum;

it('exposes the contact section keys', function () {
    expect(ContactSectionEnum::EMPLOYEES)->toBe('employees');
    expect(ContactSectionEnum::COLLABORATIONS)->toBe('collaborations');
    expect(ContactSectionEnum::BOARD_MEMBERS)->toBe('board_members');
})->group('unit', 'enums');
