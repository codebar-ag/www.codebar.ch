<?php

declare(strict_types=1);

use Database\Seeders\PagesTableSeeder;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

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
    seed(PagesTableSeeder::class);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('legal/imprint');
    expect($content)->toContain('legal/privacy');
    expect($content)->toContain('media');
    expect($content)->toContain('rechtliches/impressum');
    expect($content)->toContain('rechtliches/datenschutz');
    expect($content)->toContain('medien');
})->group('sitemap');

it('includes the newly activated terms and jobs pages in the sitemap', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('legal/terms');
    expect($content)->toContain('jobs');
    expect($content)->toContain('rechtliches/geschaeftsbedingungen');
    expect($content)->toContain('stellen');
})->group('sitemap');

it('does not include disabled pages in the sitemap', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->not->toContain('produkte');
    expect($content)->not->toContain('technologien');
    expect($content)->not->toContain('co-working');
    // The open source listing has no entries and its controller redirects.
    expect($content)->not->toContain('open-source-beitraege');
    expect($content)->not->toContain('open-source-contributions');
})->group('sitemap');

it('includes the reactivated team and news pages in the sitemap', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    $content = get('sitemap.xml')->assertOk()->getContent();

    expect($content)->toContain('ueber-uns');
    expect($content)->toContain('about-us');
    expect($content)->toContain('aktuelles');
    expect($content)->toContain('news');
})->group('sitemap');

it('includes the AI pages in the sitemap', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    $response = get('sitemap.xml');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('/ai<');
    expect($content)->toContain('/ai/llm');
    expect($content)->toContain('/ai/llm-analytics');
    expect($content)->toContain('/ki<');
    expect($content)->toContain('/ki/llm');
    expect($content)->toContain('/ki/llm-analytics');
})->group('sitemap');
