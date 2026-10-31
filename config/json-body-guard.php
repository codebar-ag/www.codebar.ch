<?php

declare(strict_types=1);

return [

    'enabled' => env('JSON_BODY_GUARD_ENABLED', true),

    'max_bytes' => 64 * 1024,

    'allow' => [
        // '/webhooks/*',
        // '/api/graphql',
    ],

];
