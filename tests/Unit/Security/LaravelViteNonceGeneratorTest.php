<?php

declare(strict_types=1);

use App\Security\Generator\LaravelViteNonceGenerator;
use Illuminate\Support\Facades\Vite;

it('returns the vite csp nonce when one is already set', function () {
    Vite::useCspNonce('a-fixed-nonce');

    expect((new LaravelViteNonceGenerator)->generate())->toBe('a-fixed-nonce');
})->group('unit', 'security');

it('generates a nonce when none is set yet', function () {
    $nonce = (new LaravelViteNonceGenerator)->generate();

    expect($nonce)->toBeString();
    expect($nonce)->not->toBeEmpty();
    expect(Vite::cspNonce())->toBe($nonce);
})->group('unit', 'security');
