<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services overrides
    |--------------------------------------------------------------------------
    |
    | Every service not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/services.php) — e.g. postmark,
    | ses, resend, slack. "microsoft" is added here since it isn't a Laravel
    | default.
    |
    */

    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => env('MICROSOFT_REDIRECT_URI'),
        'tenant' => env('MICROSOFT_TENANT_ID'),
        'include_tenant_info' => true,
    ],

];
