<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services overrides
    |--------------------------------------------------------------------------
    |
    | Every service not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/services.php) — e.g. postmark,
    | ses, resend, slack.
    |
    */

    'litellm' => [
        'url' => env('LITELLM_URL', 'https://llm.codebar.net'),
        'master_key' => env('LITELLM_MASTER_KEY'),
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN'),
    ],

];
