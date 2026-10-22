<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    config(['default.nightwatch.url' => 'https://nightwatch.test']);
    Process::fake();
});

it('fails when the client registration is rejected', function () {
    Http::fake(['nightwatch.test/oauth/register' => Http::response(['error' => 'invalid_client_metadata'], 400)]);

    expect(Artisan::call('nightwatch:login', ['--timeout' => 1]))->toBe(1)
        ->and(Artisan::output())->toContain('Client registration failed');
})->group('unit', 'console');

it('fails when no browser callback arrives in time', function () {
    Http::fake(['nightwatch.test/oauth/register' => Http::response(['client_id' => 'client'])]);

    expect(Artisan::call('nightwatch:login', ['--timeout' => 1]))->toBe(1)
        ->and(Artisan::output())->toContain('No browser callback received in time.');

    Http::assertSent(fn (Request $request): bool => data_get($request->data(), 'token_endpoint_auth_method') === 'none'
        && is_string($redirectUri = data_get($request->data(), 'redirect_uris.0'))
        && str_starts_with($redirectUri, 'http://127.0.0.1:'));
})->group('unit', 'console');
