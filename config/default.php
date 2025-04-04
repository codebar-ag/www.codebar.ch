<?php

use App\Enums\EnvironmentEnum;
use Mazedlx\FeaturePolicy\Value;
use Spatie\Csp\Keyword;

return [

    'cache' => [
        'production' => 604800, // 1 week
        'staging' => 86400, // 1 day
        'default' => 1, // 1 second
    ],

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
            'url' => env('LARAVEL_DEFAULT_FATHOM_URL', 'https://cdn-eu.usefathom.com/script.js'),
            'site_id' => env('LARAVEL_DEFAULT_FATHOM_SITE_ID'),
            'environments' => [
                EnvironmentEnum::STAGING->value,
                EnvironmentEnum::PRODUCTION->value,
            ],
        ],
    ],

    'auth' => [
        'providers' => [

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
    'security_headers' => [
        'connect' => [
            Keyword::SELF,
            'wss:',
            'api.userback.io/',
            'ka-p.fontawesome.com/',
            'res.cloudinary.com/codebar/',
            'prod-s3-public.s3-website-bc01.gos3.io/',
            'unpkg.com/',
            env('APP_URL'),
            'srv-dev-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-dev-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
        ],
        'default' => [
            Keyword::SELF,
        ],
        'form_action' => [
            Keyword::SELF,
        ],
        'img' => [
            Keyword::SELF, 'data:',
            'blob:', 'localhost/',
            'prod-s3-public.s3-website-bc01.gos3.io/',
            'cdn.usefathom.com/',
            'cdn-eu.usefathom.com/',
            'www.gravatar.com/avatar/',
            'secure.gravatar.com/avatar/',
            'res.cloudinary.com/codebar/',
            env('APP_URL'),
            'srv-dev-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-dev-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
        ],
        'media' => [
            Keyword::SELF,
            env('APP_URL'),
            'srv-dev-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-dev-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-stage-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.digitaloceanspaces.com/',
            'srv-prod-space-fra-001.fra1.cdn.digitaloceanspaces.com/',
        ],
        'object' => [
            Keyword::NONE,
        ],
        'font' => [
            Keyword::SELF,
            'data:',
            'use.typekit.net/',
            'fonts.gstatic.com/',
            'fonts.googleapis.com/'.
            'ka-p.fontawesome.com/',
            'static.userback.io/',
            'rsms.me/inter/font-files/',
            'prod-s3-public.s3-website-bc01.gos3.io/portal-vanta-ch/production',
        ],
        'script' => [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
            Keyword::UNSAFE_EVAL,
            'cdn.usefathom.com/',
            'cdn-eu.usefathom.com/',
            'static.userback.io/',
            'kit.fontawesome.com/',
            'cdnjs.cloudflare.com/ajax/libs/dragula/',
            'prod-s3-public.s3-website-bc01.gos3.io/',
            'unpkg.com/',
        ],
        'style_elem' => [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
            'static.userback.io/',
            'fonts.googleapis.com/',
            'fonts.sandbox.google.com/',
            'rsms.me/inter/',
        ],
        'style' => [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
            'static.userback.io/',
            'fonts.sandbox.google.com/',
            'fonts.googleapis.com/'.
            'rsms.me/inter/',
        ],

    ],
];
