<?php

declare(strict_types=1);

use CodebarAg\JsonBodyGuard\Tests\Support\FixtureServer;

function fixtureServer(): FixtureServer
{
    /** @var FixtureServer|null $server */
    static $server = null;

    return $server ??= FixtureServer::start();
}

afterAll(function () {
    fixtureServer()->stop();
});

it('answers 415 for a json body on a path that is not allowed', function () {
    $response = fixtureServer()->post('/', '{"a":1}', 'application/json');

    expect($response->status)->toBe(415)
        ->and($response->body)->toBe('Unsupported Media Type');
});

it('answers 413 for an oversized json body on an allowed path', function () {
    $response = fixtureServer()->post('/webhooks/postmark', str_repeat('x', 2048), 'application/json');

    expect($response->status)->toBe(413)
        ->and($response->body)->toBe('Payload Too Large');
});

it('answers 413 for an oversized chunked json body without content length', function () {
    $response = fixtureServer()->post('/webhooks/postmark', str_repeat('x', 2048), 'application/json', chunked: true);

    expect($response->status)->toBe(413);
});

it('lets a small json body through on an allowed path', function () {
    $response = fixtureServer()->post('/webhooks/postmark', '{"a":1}', 'application/json');

    expect($response->status)->toBe(200)
        ->and($response->body)->toBe('reached-app');
});

it('lets form posts and plain requests through everywhere', function () {
    expect(fixtureServer()->post('/', 'a=1&b=2', 'application/x-www-form-urlencoded')->status)->toBe(200)
        ->and(fixtureServer()->get('/')->status)->toBe(200);
});
