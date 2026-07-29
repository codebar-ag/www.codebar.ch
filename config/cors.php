<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) overrides
    |--------------------------------------------------------------------------
    |
    | Every option not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/cors.php).
    |
    */

    'allowed_origins' => [
        env('APP_URL'),
        'srv-dev-space-fra-001.fra1.digitaloceanspaces.com/',
        'srv-dev-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
        'srv-stage-space-fra-001.fra1.digitaloceanspaces.com/',
        'srv-stage-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
        'srv-prod-space-fra-001.fra1.digitaloceanspaces.com/',
        'srv-prod-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
    ],

];
