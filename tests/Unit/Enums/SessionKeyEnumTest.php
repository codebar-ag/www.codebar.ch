<?php

use App\Enums\SessionKeyEnum;

it('exposes the expected cases', function () {
    expect(SessionKeyEnum::cases())->toBe([
        SessionKeyEnum::LANGUAGE,
    ]);
})->group('enums', 'session-key-enum');
