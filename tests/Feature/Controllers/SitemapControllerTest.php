<?php

use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

it('generates and caches the sitemap XML', function () {
    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml');

    expect($response->status())->toBe(200);

    expect(Cache::has('sitemap_xml'))->toBeTrue();

})->group('sitemap');

it('serves cached sitemap without rebuilding', function () {
    Cache::put('sitemap_xml', 'fake-xml-response', now()->addHour());

    $response = get('sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml');
    expect($response->getContent())->toBe('fake-xml-response');
})->group('sitemap');
