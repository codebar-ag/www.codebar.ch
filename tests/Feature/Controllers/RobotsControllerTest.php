<?php

use function Pest\Laravel\get;

it('serves robots.txt with sitemap reference', function () {
    config(['app.url' => 'https://codebar.ch']);

    $response = get('robots.txt');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

    $content = $response->getContent();

    expect($content)->toContain('User-agent: *');
    expect($content)->toContain('Sitemap: https://codebar.ch/sitemap.xml');
})->group('robots');
