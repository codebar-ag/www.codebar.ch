<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Log channel overrides
    |--------------------------------------------------------------------------
    |
    | Every channel not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/logging.php).
    |
    | Error reporting goes to Laravel Nightwatch, which hooks the exception
    | handler directly rather than through a log channel — so there is no
    | reporting channel to add to LOG_STACK.
    |
    */

    'channels' => [

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

    ],

];
