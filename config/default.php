<?php

declare(strict_types=1);

use App\Enums\EnvironmentEnum;
use Mazedlx\FeaturePolicy\Value;

return [

    'services' => [
        'fathom' => [
            'site_id' => env('LARAVEL_DEFAULT_FATHOM_SITE_ID'),
            'environments' => [
                EnvironmentEnum::PRODUCTION->value,
            ],
        ],
        'codebar_analytics' => [
            'url' => env('CODEBAR_ANALYTICS_URL'),
            'environments' => [
                EnvironmentEnum::PRODUCTION->value,
            ],
        ],
    ],

    'feature_policy' => [
        'camera' => [
            Value::SELF,
        ],
        'fullscreen' => [
            Value::SELF,
        ],
    ],

    /*
     * NightwatchIssuesCheck asks Nightwatch for open issues of this application.
     * The check is only registered once api_token and application_id are set.
     */
    'nightwatch' => [
        'url' => env('NIGHTWATCH_API_URL', 'https://nightwatch.laravel.com'),
        // OAuth token from `php artisan nightwatch:login` (valid one year).
        'api_token' => env('NIGHTWATCH_API_TOKEN'),
        'application_id' => env('NIGHTWATCH_APPLICATION_ID'),
        // Optional: only count issues of this environment (e.g. production).
        'environment_id' => env('NIGHTWATCH_ENVIRONMENT_ID'),
    ],
];
