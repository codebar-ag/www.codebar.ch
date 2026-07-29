<?php

declare(strict_types=1);

use App\Actions\PageAction;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;

it('builds a PageDTO for a news item', function () {
    $news = News::factory()->create();

    $page = (new PageAction)->news($news);

    expect($page->title)->toBe($news->title);
    expect($page->description)->toBe($news->teaser);
    expect($page->routeKey)->toBe('news.show');
    expect($page->referencePages)->toBeNull();
});

it('builds a PageDTO for a product', function () {
    $product = Product::factory()->create();

    $page = (new PageAction)->product($product);

    expect($page->title)->toBe($product->name);
    expect($page->routeKey)->toBe('products.show');
});

it('builds a PageDTO for a service', function () {
    $service = Service::factory()->create();

    $page = (new PageAction)->service($service);

    expect($page->title)->toBe($service->name);
    expect($page->routeKey)->toBe('services.show');
});

it('builds a PageDTO for a technology', function () {
    $technology = Technology::factory()->create();

    $page = (new PageAction)->technology($technology);

    expect($page->title)->toBe($technology->title);
    expect($page->routeKey)->toBe('technologies.show');
});

it('builds a PageDTO for an open source project', function () {
    $openSource = OpenSource::factory()->create();

    $page = (new PageAction)->openSource($openSource);

    expect($page->title)->toBe($openSource->title);
    expect($page->routeKey)->toBe('open-source.show');
});

it('includes the alternate locale as a reference page when requested', function () {
    $news = News::factory()->create([
        'title' => ['de_CH' => 'Titel DE', 'en_CH' => 'Title EN'],
        'teaser' => ['de_CH' => 'Teaser DE', 'en_CH' => 'Teaser EN'],
    ]);

    $page = (new PageAction(locale: 'de_CH'))->news($news, withReferences: true);

    expect($page->referencePages)->toHaveCount(1);
    expect($page->referencePages?->firstOrFail()->locale)->toBe('en_CH');
    expect($page->referencePages?->firstOrFail()->title)->toBe('Title EN');
});
