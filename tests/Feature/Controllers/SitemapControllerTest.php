<?php

use Database\Seeders\Codebar\ConfigurationsTableSeeder;
use Database\Seeders\Codebar\PagesTableSeeder;
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

it('includes live legal and media pages in the sitemap', function () {
    $this->seed([
        ConfigurationsTableSeeder::class,
        PagesTableSeeder::class,
    ]);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('legal/imprint');
    expect($content)->toContain('legal/privacy');
    expect($content)->toContain('media');
    expect($content)->toContain('rechtlichtes/impressum');
    expect($content)->toContain('rechtlichtes/datenschutz');
    expect($content)->toContain('medien');
})->group('sitemap');

it('includes the AI pages in the sitemap', function () {
    $this->seed([
        ConfigurationsTableSeeder::class,
        PagesTableSeeder::class,
    ]);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('/ai<');
    expect($content)->toContain('/ai/llm');
    expect($content)->toContain('/ki<');
    expect($content)->toContain('/ki/llm');
})->group('sitemap');
