<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Mailer overrides
    |--------------------------------------------------------------------------
    |
    | Every mailer not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/mail.php). "smtp" must be kept
    | whole (this merge isn't deep) since it forces MAIL_ENCRYPTION.
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

    ],

];
