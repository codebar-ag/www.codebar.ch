<?php

use App\Http\Middleware\SetLanguage;
use CodebarAg\LaravelFeaturePolicy\AddFeaturePolicyHeaders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Csp\AddCspHeaders;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\ResponseCache\Middlewares\CacheResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(RunHealthChecksCommand::class)->everyFiveMinutes();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: env('TRUSTED_PROXIES') ?: '*');

        $middleware->web(append: [
            AddCspHeaders::class,
            AddFeaturePolicyHeaders::class,
            SetLanguage::class,
            CacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
