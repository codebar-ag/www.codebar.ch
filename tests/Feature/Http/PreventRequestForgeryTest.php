<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

beforeEach(function () {
    Route::middleware('web')->post('/csrf-check', fn (): string => 'passed');

    app()->instance('env', 'production');
});

it('rejects a post without a csrf token', function () {
    post('/csrf-check')->assertStatus(419);
})->group('security', 'middleware');

it('rejects a post with a mismatched csrf token', function () {
    withSession([]);

    post('/csrf-check', ['_token' => 'not-the-session-token'])->assertStatus(419);
})->group('security', 'middleware');

it('lets a post with a valid csrf token through without adding the xsrf cookie', function () {
    withSession([]);

    post('/csrf-check', ['_token' => csrf_token()])
        ->assertOk()
        ->assertSee('passed')
        ->assertCookieMissing('XSRF-TOKEN');
})->group('security', 'middleware');
