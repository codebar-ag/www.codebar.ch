<?php

declare(strict_types=1);

use CodebarAg\JsonBodyGuard\JsonBodyGuard;
use CodebarAg\JsonBodyGuard\Reason;

/**
 * @return array<string, string>
 */
function jsonServer(string $uri = '/', ?int $length = 10, string $contentType = 'application/json'): array
{
    $server = ['REQUEST_URI' => $uri, 'REQUEST_METHOD' => 'POST', 'CONTENT_TYPE' => $contentType];

    if ($length !== null) {
        $server['CONTENT_LENGTH'] = (string) $length;
    }

    return $server;
}

it('ignores requests that do not declare a json body', function (array $server) {
    expect(JsonBodyGuard::denyAll()->inspect($server))->toBeNull();
})->with([
    'form post' => [['REQUEST_URI' => '/', 'CONTENT_TYPE' => 'application/x-www-form-urlencoded', 'CONTENT_LENGTH' => '999999']],
    'multipart upload' => [['REQUEST_URI' => '/', 'CONTENT_TYPE' => 'multipart/form-data; boundary=x', 'CONTENT_LENGTH' => '999999']],
    'plain get' => [['REQUEST_URI' => '/']],
    'no server data' => [[]],
    'non-string content type' => [['REQUEST_URI' => '/', 'CONTENT_TYPE' => ['application/json'], 'CONTENT_LENGTH' => '10']],
]);

it('ignores a json content type without a body', function () {
    expect(JsonBodyGuard::denyAll()->inspect(jsonServer('/', 0)))->toBeNull();
});

it('rejects a json body on any path when nothing is allowed', function (string $contentType) {
    $rejection = JsonBodyGuard::denyAll()->inspect(jsonServer('/', 10, $contentType));

    expect($rejection)->not->toBeNull()
        ->and($rejection?->reason)->toBe(Reason::PathNotAllowed)
        ->and($rejection?->status())->toBe(415)
        ->and($rejection?->message())->toBe('Unsupported Media Type')
        ->and($rejection?->path)->toBe('/');
})->with([
    'application/json',
    'Application/JSON; charset=utf-8',
    'application/ld+json',
    'application/vnd.api+json',
    'text/json',
]);

it('lets a json body through on an allowed path', function (string $uri) {
    $guard = JsonBodyGuard::allow('/webhooks/*', '/api/graphql', 'health/');

    expect($guard->inspect(jsonServer($uri)))->toBeNull();
})->with([
    '/webhooks/postmark',
    '/webhooks/stripe/events?signature=abc',
    '/api/graphql',
    '/api/graphql/',
    '/health',
    '/webhooks/a%20b',
]);

it('rejects a json body on a path outside the allow list', function (string $uri) {
    $rejection = JsonBodyGuard::allow('/webhooks/*', '/api/graphql')->inspect(jsonServer($uri));

    expect($rejection?->reason)->toBe(Reason::PathNotAllowed)
        ->and($rejection?->path)->toBe(parse_url($uri, PHP_URL_PATH) === '/' ? '/' : rtrim(rawurldecode((string) parse_url($uri, PHP_URL_PATH)), '/'));
})->with([
    '/',
    '/webhooks',
    '/api/graphql/extra',
    '/api/graphqlx',
    '/login?next=/webhooks/postmark',
]);

it('rejects an oversized json body on an allowed path with 413', function () {
    $guard = JsonBodyGuard::allow('/webhooks/*')->maxBytes(1024);

    expect($guard->inspect(jsonServer('/webhooks/postmark', 1024)))->toBeNull();

    $rejection = $guard->inspect(jsonServer('/webhooks/postmark', 1025));

    expect($rejection?->reason)->toBe(Reason::TooLarge)
        ->and($rejection?->status())->toBe(413)
        ->and($rejection?->message())->toBe('Payload Too Large');
});

it('defaults to a 64 KB limit', function () {
    expect(JsonBodyGuard::DEFAULT_MAX_BYTES)->toBe(65536)
        ->and(JsonBodyGuard::allow('/')->inspect(jsonServer('/', 65536)))->toBeNull()
        ->and(JsonBodyGuard::allow('/')->inspect(jsonServer('/', 65537))?->reason)->toBe(Reason::TooLarge);
});

it('reads at most the limit plus one byte when no content length is given', function () {
    $stream = tempnam(sys_get_temp_dir(), 'json-body');
    file_put_contents($stream, str_repeat('{', 2000));

    $guard = JsonBodyGuard::allow('/webhooks/*')->maxBytes(1024)->readingFrom($stream);

    expect($guard->inspect(jsonServer('/webhooks/postmark', null))?->reason)->toBe(Reason::TooLarge)
        ->and($guard->inspect(jsonServer('/other', null))?->reason)->toBe(Reason::PathNotAllowed);

    file_put_contents($stream, '{"ok":true}');

    expect($guard->inspect(jsonServer('/webhooks/postmark', null)))->toBeNull();

    file_put_contents($stream, '');

    expect($guard->inspect(jsonServer('/other', null)))->toBeNull();

    unlink($stream);
});

it('treats an unreadable input stream as an empty body', function () {
    $guard = JsonBodyGuard::denyAll()->readingFrom(sys_get_temp_dir().'/does-not-exist-'.uniqid());

    expect($guard->inspect(jsonServer('/', null)))->toBeNull();
});

it('falls back to PATH_INFO and the root path', function () {
    $guard = JsonBodyGuard::allow('/webhooks/*');

    expect($guard->inspect(['PATH_INFO' => '/webhooks/x', 'CONTENT_TYPE' => 'application/json', 'CONTENT_LENGTH' => '5']))->toBeNull()
        ->and($guard->inspect(['CONTENT_TYPE' => 'application/json', 'CONTENT_LENGTH' => '5'])?->path)->toBe('/')
        ->and($guard->inspect(['REQUEST_URI' => 42, 'CONTENT_TYPE' => 'application/json', 'CONTENT_LENGTH' => '5'])?->path)->toBe('/');
});

it('matches wildcard patterns across path segments', function () {
    $guard = JsonBodyGuard::allow('/api/*/events', '*.json');

    expect($guard->allows('/api/v1/events'))->toBeTrue()
        ->and($guard->allows('/api/v1/nested/events'))->toBeTrue()
        ->and($guard->allows('/api/v1/events/extra'))->toBeFalse()
        ->and($guard->allows('/feed.json'))->toBeTrue()
        ->and($guard->allows('/feed.json/'))->toBeTrue()
        ->and($guard->allows('/feed.xml'))->toBeFalse();
});

it('does nothing in guard when the request passes', function () {
    JsonBodyGuard::allow('/webhooks/*')->guard(jsonServer('/webhooks/postmark'));
    JsonBodyGuard::denyAll()->guard(['REQUEST_URI' => '/']);

    expect(true)->toBeTrue();
});

it('builds a guard from a config array', function () {
    $guard = JsonBodyGuard::fromConfig(['allow' => ['/webhooks/*', 42, null], 'max_bytes' => '1024']);

    expect($guard->allows('/webhooks/postmark'))->toBeTrue()
        ->and($guard->allows('/42'))->toBeFalse()
        ->and($guard->inspect(jsonServer('/webhooks/postmark', 1024)))->toBeNull()
        ->and($guard->inspect(jsonServer('/webhooks/postmark', 1025))?->reason)->toBe(Reason::TooLarge);
});

it('falls back to the defaults for a config without keys', function () {
    $guard = JsonBodyGuard::fromConfig(['allow' => 'not-a-list']);

    expect($guard->inspect(jsonServer('/', 10))?->reason)->toBe(Reason::PathNotAllowed)
        ->and($guard->inspect(jsonServer('/', 0)))->toBeNull();
});

it('does nothing when disabled through the config', function () {
    $guard = JsonBodyGuard::fromConfig(['enabled' => false]);

    expect($guard->inspect(jsonServer('/', JsonBodyGuard::DEFAULT_MAX_BYTES * 10)))->toBeNull();
});

it('loads a config file', function () {
    $file = tempnam(sys_get_temp_dir(), 'json-body-guard');
    file_put_contents($file, "<?php return ['allow' => ['/hooks/*'], 'max_bytes' => 10];");

    $guard = JsonBodyGuard::fromConfigFile($file);

    expect($guard->inspect(jsonServer('/hooks/a', 10)))->toBeNull()
        ->and($guard->inspect(jsonServer('/hooks/a', 11))?->reason)->toBe(Reason::TooLarge)
        ->and($guard->inspect(jsonServer('/', 1))?->reason)->toBe(Reason::PathNotAllowed);

    file_put_contents($file, '<?php return "nope";');

    expect(JsonBodyGuard::fromConfigFile($file)->inspect(jsonServer('/', 1))?->reason)->toBe(Reason::PathNotAllowed);

    unlink($file);
});
