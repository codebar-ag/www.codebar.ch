<?php

use App\Enums\EnvironmentEnum;
use Mazedlx\FeaturePolicy\Value;

return [

    'services' => [
        'userback' => [
            'url' => env('USERBACK_URL', 'https://static.userback.io/widget/v1.js'),
            'token' => env('USERBACK_TOKEN'),
            'environments' => [
                EnvironmentEnum::STAGING->value,
                EnvironmentEnum::PRODUCTION->value,
            ],
        ],
        'fathom' => [
            'site_id' => env('LARAVEL_DEFAULT_FATHOM_SITE_ID'),
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
];
