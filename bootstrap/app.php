<?php

use App\Http\Middleware\SetLanguage;
use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Mazedlx\FeaturePolicy\AddFeaturePolicyHeaders;
use Spatie\Csp\AddCspHeaders;
use Spatie\Permission\Middleware\RoleMiddleware;

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
            SetLanguage::class,
        ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
