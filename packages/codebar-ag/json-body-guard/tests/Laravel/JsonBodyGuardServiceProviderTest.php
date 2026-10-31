<?php

declare(strict_types=1);

use CodebarAg\JsonBodyGuard\JsonBodyGuard;
use CodebarAg\JsonBodyGuard\Laravel\JsonBodyGuardServiceProvider;

it('exposes the package defaults through the config repository', function () {
    expect(config('json-body-guard.enabled'))->toBeTrue()
        ->and(config('json-body-guard.max_bytes'))->toBe(JsonBodyGuard::DEFAULT_MAX_BYTES)
        ->and(config('json-body-guard.allow'))->toBeArray();
});

it('ships a config file that the guard can load before the framework boots', function () {
    $guard = JsonBodyGuard::fromConfigFile(JsonBodyGuardServiceProvider::configPath());

    expect($guard->inspect(['REQUEST_URI' => '/', 'CONTENT_TYPE' => 'application/json', 'CONTENT_LENGTH' => '10']))
        ->not->toBeNull();
});

it('publishes the config file under its own tag', function () {
    $paths = JsonBodyGuardServiceProvider::pathsToPublish(JsonBodyGuardServiceProvider::class, 'json-body-guard-config');

    expect($paths)->toHaveKey(JsonBodyGuardServiceProvider::configPath())
        ->and($paths[JsonBodyGuardServiceProvider::configPath()])->toBe(config_path('json-body-guard.php'));
});
