<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

it('warms the cached sitemap xml', function () {
    Cache::forget('sitemap_xml');

    Artisan::call('sitemap:generate');

    expect(Cache::get('sitemap_xml'))->toBeString();
})->group('unit', 'console');
