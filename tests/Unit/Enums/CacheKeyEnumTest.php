<?php

declare(strict_types=1);

use App\Enums\CacheKeyEnum;

it('exposes the valid filesystems default cache key', function () {
    expect(CacheKeyEnum::VALID_FILESYSTEMS_DEFAULT)->toBe('valid_filesystems_defaullt');
})->group('unit', 'enums');
