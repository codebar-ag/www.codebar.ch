<?php

use App\Actions\PageAction;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Reference;
use App\Models\Service;
use App\Models\Technology;

it('builds a PageDTO for a news item', function () {
    $news = News::factory()->create(['locale' => 'de_CH']);

    $page = (new PageAction)->news($news);

    expect($page->title)->toBe($news->title);
    expect($page->description)->toBe($news->teaser);
    expect($page->routeKey)->toBe('news.show');
    expect($page->referencePages)->toBeNull();
});

it('builds a PageDTO for a product', function () {
    $product = Product::factory()->create(['locale' => 'de_CH']);

    $page = (new PageAction)->product($product);

    expect($page->title)->toBe($product->name);
    expect($page->routeKey)->toBe('products.show');
});

it('builds a PageDTO for a service', function () {
    $service = Service::factory()->create(['locale' => 'de_CH']);

    $page = (new PageAction)->service($service);

    expect($page->title)->toBe($service->name);
    expect($page->routeKey)->toBe('services.show');
});

it('builds a PageDTO for a technology', function () {
    $technology = Technology::factory()->create(['locale' => 'de_CH']);

    $page = (new PageAction)->technology($technology);

    expect($page->title)->toBe($technology->title);
    expect($page->routeKey)->toBe('technologies.show');
});

it('builds a PageDTO for an open source project', function () {
    $openSource = OpenSource::factory()->create(['locale' => 'de_CH']);

    $page = (new PageAction)->openSource($openSource);

    expect($page->title)->toBe($openSource->title);
    expect($page->routeKey)->toBe('open-source.show');
});

it('includes only same-type reference pages when requested', function () {
    $news = News::factory()->create(['locale' => 'de_CH']);
    $referencedNews = News::factory()->create(['locale' => 'en_CH']);

    $news->references()->create([
        'reference_type' => News::class,
        'reference_id' => $referencedNews->id,
        'reference_locale' => $referencedNews->locale->value,
    ]);

    $page = (new PageAction)->news($news, withReferences: true);

    expect($page->referencePages)->toHaveCount(1);
    expect($page->referencePages?->firstOrFail()->locale)->toBe('en_CH');
});

it('skips reference pages pointing at a different model type', function () {
    $product = Product::factory()->create(['locale' => 'de_CH']);
    $news = News::factory()->create(['locale' => 'de_CH']);

    Reference::create([
        'source_type' => Product::class,
        'source_id' => $product->id,
        'reference_type' => News::class,
        'reference_id' => $news->id,
        'reference_locale' => $news->locale->value,
    ]);

    $page = (new PageAction)->product($product, withReferences: true);

    expect($page->referencePages)->toHaveCount(0);
});
