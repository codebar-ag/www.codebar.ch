<?php

declare(strict_types=1);

use App\Rules\TurnstileRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @param  array<string, mixed>  $data
 */
function turnstileFails(array $data): bool
{
    return Validator::make($data, [TurnstileRule::FIELD => [new TurnstileRule]])->fails();
}

beforeEach(function () {
    config(['services.turnstile.secret_key' => 'test-secret']);
});

it('passes when cloudflare confirms the token', function () {
    Http::fake([TurnstileRule::VERIFY_URL => Http::response(['success' => true])]);

    expect(turnstileFails([TurnstileRule::FIELD => 'valid-token']))->toBeFalse();

    Http::assertSent(fn (Request $request) => $request['secret'] === 'test-secret'
        && $request['response'] === 'valid-token');
})->group('unit', 'turnstile');

it('fails when cloudflare rejects the token', function () {
    Http::fake([TurnstileRule::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

    expect(turnstileFails([TurnstileRule::FIELD => 'forged-token']))->toBeTrue();
})->group('unit', 'turnstile');

it('fails without calling cloudflare when the token is missing', function () {
    Http::fake();

    expect(turnstileFails([]))->toBeTrue();

    Http::assertNothingSent();
})->group('unit', 'turnstile');

it('fails without calling cloudflare when the token is empty or not a string', function (mixed $token) {
    Http::fake();

    expect(turnstileFails([TurnstileRule::FIELD => $token]))->toBeTrue();

    Http::assertNothingSent();
})->with([
    'empty' => [''],
    'not a string' => [['x']],
])->group('unit', 'turnstile');

it('fails when cloudflare is unreachable', function () {
    Http::fake(fn () => throw new ConnectionException('timeout'));

    expect(turnstileFails([TurnstileRule::FIELD => 'valid-token']))->toBeTrue();
})->group('unit', 'turnstile');

it('adds the rule only when a secret is configured', function () {
    expect(TurnstileRule::rules())->toHaveKey(TurnstileRule::FIELD);

    config(['services.turnstile.secret_key' => null]);

    expect(TurnstileRule::rules())->toBe([]);
})->group('unit', 'turnstile');
