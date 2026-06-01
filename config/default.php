<?php

use App\Enums\EnvironmentEnum;

return [

    'services' => [
        'fathom' => [
            'url' => env('LARAVEL_DEFAULT_FATHOM_URL', 'https://cdn-eu.usefathom.com/script.js'),
            'site_id' => env('LARAVEL_DEFAULT_FATHOM_SITE_ID'),
            'environments' => [
                EnvironmentEnum::STAGING->value,
                EnvironmentEnum::PRODUCTION->value,
            ],
        ],
    ],
];
