<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLanguage;
use App\Http\Middleware\ValidateCsrfToken;
use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as FrameworkValidateCsrfToken;
use Mazedlx\FeaturePolicy\AddFeaturePolicyHeaders;
use Spatie\Csp\AddCspHeaders;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\ResponseCache\Middlewares\CacheResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AppServiceProvider::class,
        EventServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        then: function () {}
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            AddCspHeaders::class,
            AddFeaturePolicyHeaders::class,
            SecurityHeaders::class,
            SetLanguage::class,
            CacheResponse::class,
        ]);
        $middleware->replace(FrameworkValidateCsrfToken::class, ValidateCsrfToken::class);
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
