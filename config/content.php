<?php

return [
    'path' => env('CONTENT_PATH', base_path('content')),
    'cache_ttl' => (int) env('CONTENT_CACHE_TTL', 3600),
];
