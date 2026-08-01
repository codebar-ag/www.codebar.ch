<?php

declare(strict_types=1);

use App\Http\Middleware\PreventRequestForgery;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLanguage;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as FrameworkPreventRequestForgery;
use Mazedlx\FeaturePolicy\AddFeaturePolicyHeaders;
use Spatie\Csp\AddCspHeaders;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\ResponseCache\Middlewares\CacheResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AppServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            AddCspHeaders::class,
            AddFeaturePolicyHeaders::class,
            SecurityHeaders::class,
            SetLanguage::class,
            CacheResponse::class,
        ], replace: [
            FrameworkPreventRequestForgery::class => PreventRequestForgery::class,
        ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
