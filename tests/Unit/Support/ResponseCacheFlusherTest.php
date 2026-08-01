<?php

declare(strict_types=1);

use App\Support\ResponseCacheFlusher;
use Spatie\ResponseCache\Facades\ResponseCache;

it('clears immediately outside a batch', function () {
    ResponseCache::shouldReceive('clear')->once();

    ResponseCacheFlusher::flush();
})->group('unit', 'support');

it('collapses many flushes inside a batch into one', function () {
    ResponseCache::shouldReceive('clear')->once();

    ResponseCacheFlusher::batch(function (): void {
        foreach (range(1, 50) as $ignored) {
            ResponseCacheFlusher::flush();
        }
    });
})->group('unit', 'support');

it('does not clear when a batch changed nothing', function () {
    ResponseCache::shouldReceive('clear')->never();

    ResponseCacheFlusher::batch(fn () => null);
})->group('unit', 'support');

it('still clears when the batch throws', function () {
    ResponseCache::shouldReceive('clear')->once();

    expect(fn () => ResponseCacheFlusher::batch(function (): void {
        ResponseCacheFlusher::flush();

        throw new RuntimeException('import blew up halfway');
    }))->toThrow(RuntimeException::class);
})->group('unit', 'support');

it('returns the callback result', function () {
    ResponseCache::shouldReceive('clear')->never();

    expect(ResponseCacheFlusher::batch(fn (): int => 42))->toBe(42);
})->group('unit', 'support');
