<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('adds security headers on public pages', function () {
    $response = get(route(Str::slug(LocaleEnum::DE->value).'.start.index'));

    $response->assertOk();
    $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('X-Frame-Options', 'DENY');
    expect($response->headers->get('Content-Security-Policy'))->toContain("frame-ancestors 'self'");
})->group('security');

it('adds strict transport security on secure requests', function () {
    $response = get(route(Str::slug(LocaleEnum::DE->value).'.start.index'), [
        'HTTPS' => 'on',
        'SERVER_PORT' => 443,
    ]);

    $response->assertOk();
    $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
})->group('security');
