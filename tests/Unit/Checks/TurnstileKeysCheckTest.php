<?php

declare(strict_types=1);

use App\Checks\TurnstileKeysCheck;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Health\Enums\Status;

beforeEach(function () {
    config(['services.turnstile.site_key' => '0x4AAAAAAAsite', 'services.turnstile.secret_key' => '0x4AAAAAAAsecret']);
});

it('passes when Cloudflare accepts the secret', function () {
    Http::fake([TurnstileKeysCheck::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

    $result = (new TurnstileKeysCheck)->run();

    expect($result->status->equals(Status::ok()))->toBeTrue()
        ->and($result->shortSummary)->toBe('secret valid');
    Http::assertSent(fn (Request $request): bool => $request['secret'] === '0x4AAAAAAAsecret' && $request['response'] === 'health-check');
})->group('unit', 'checks');

it('fails when Cloudflare rejects the secret', function () {
    Http::fake([TurnstileKeysCheck::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-secret']], 400)]);

    expect((new TurnstileKeysCheck)->run()->shortSummary)->toBe('secret rejected');
})->group('unit', 'checks');

it('passes without a request when Turnstile is not configured', function () {
    config(['services.turnstile.site_key' => null, 'services.turnstile.secret_key' => null]);
    Http::fake();

    expect((new TurnstileKeysCheck)->run()->status->equals(Status::ok()))->toBeTrue();
    Http::assertNothingSent();
})->group('unit', 'checks');

it('fails when the secret is set without a site key', function () {
    config(['services.turnstile.site_key' => '']);
    Http::fake();

    expect((new TurnstileKeysCheck)->run()->status->equals(Status::failed()))->toBeTrue();
    Http::assertNothingSent();
})->group('unit', 'checks');

it('warns when the site key is set without a secret', function () {
    config(['services.turnstile.secret_key' => '']);
    Http::fake();

    expect((new TurnstileKeysCheck)->run()->status->equals(Status::warning()))->toBeTrue();
    Http::assertNothingSent();
})->group('unit', 'checks');

it('fails when production runs on test keys', function () {
    app()->detectEnvironment(fn (): string => 'production');
    config(['services.turnstile.site_key' => '1x00000000000000000000AA', 'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA']);
    Http::fake();

    $summary = (new TurnstileKeysCheck)->run()->shortSummary;
    app()->detectEnvironment(fn (): string => 'testing');

    expect($summary)->toBe('test keys');
    Http::assertNothingSent();
})->group('unit', 'checks');

it('passes with test keys outside production', function () {
    config(['services.turnstile.site_key' => '1x00000000000000000000AA', 'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA']);
    Http::fake([TurnstileKeysCheck::VERIFY_URL => Http::response(['success' => true, 'error-codes' => [], 'metadata' => ['result_with_testing_key' => true]])]);

    expect((new TurnstileKeysCheck)->run()->status->equals(Status::ok()))->toBeTrue();
})->group('unit', 'checks');

it('fails when Cloudflare cannot be reached', function () {
    Http::fake(fn () => throw new ConnectionException('timeout'));

    expect((new TurnstileKeysCheck)->run()->shortSummary)->toBe('cloudflare unreachable');
})->group('unit', 'checks');

it('reports the HTTP status on an unexpected answer', function () {
    Http::fake([TurnstileKeysCheck::VERIFY_URL => Http::response('', 500)]);

    expect((new TurnstileKeysCheck)->run()->getNotificationMessage())->toContain('500');
})->group('unit', 'checks');
