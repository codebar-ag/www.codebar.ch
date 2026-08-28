<?php

declare(strict_types=1);

use App\Support\GravatarUrl;

it('hashes the trimmed and lowercased email with sha256', function () {
    expect(GravatarUrl::src('  Vincenzo@Example.COM  ', 96))->toBe(
        'https://www.gravatar.com/avatar/a26741b7ffbef67042a8d2a315943f15066ed0c3b5b290b2370f4745c80ea85d?s=96&d=mp'
    );
})->group('unit', 'support');

it('carries the requested size and the mystery-person default', function () {
    expect(GravatarUrl::src('vincenzo@example.com', 256))
        ->toStartWith('https://www.gravatar.com/avatar/')
        ->toEndWith('?s=256&d=mp');
})->group('unit', 'support');
