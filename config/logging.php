<?php

use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Log channel overrides
    |--------------------------------------------------------------------------
    |
    | Every channel not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/logging.php). "flare" is added
    | for spatie/laravel-ignition, which isn't a Laravel default channel.
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

        'flare' => [
            'driver' => 'flare',
        ],

    ],

];
