<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard\Laravel;

use Illuminate\Support\ServiceProvider;

final class JsonBodyGuardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(self::configPath(), 'json-body-guard');
    }

    public function boot(): void
    {
        $this->publishes([
            self::configPath() => $this->app->configPath('json-body-guard.php'),
        ], 'json-body-guard-config');
    }

    public static function configPath(): string
    {
        return dirname(__DIR__, 2).'/config/json-body-guard.php';
    }
}
