<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('serves robots.txt with sitemap reference in production', function () {
    config(['app.url' => 'https://codebar.ch']);
    app()->detectEnvironment(fn (): string => 'production');

    $response = get('robots.txt');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

    $content = $response->getContent();

    expect($content)->toContain('User-agent: *');
    expect($content)->toContain('Sitemap: https://codebar.ch/sitemap.xml');
    expect($content)->not->toContain('Disallow: /'.PHP_EOL);
})->group('robots');

it('disallows crawling outside production', function () {
    // A staging copy that gets indexed competes with production for the same
    // queries — the environment, not the config, decides this.
    expect(app()->isProduction())->toBeFalse();

    $content = get('robots.txt')->assertOk()->getContent();

    expect($content)->toContain('Disallow: /');
    expect($content)->not->toContain('Sitemap:');
})->group('robots');

it('keeps signed and form-only network routes out of the crawl', function () {
    app()->detectEnvironment(fn (): string => 'production');

    $content = get('robots.txt')->assertOk()->getContent();

    expect($content)->toContain('Disallow: /netzwerk/request');
    expect($content)->toContain('Disallow: /netzwerk/verwalten/');
})->group('robots');
