<?php

use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;

use function Pest\Laravel\get;

it('shows a news article', function () {
    $news = News::factory()->create(['locale' => 'de_CH']);

    get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()
        ->assertSee($news->title);
})->group('news', 'show');

it('shows a product', function () {
    $product = Product::factory()->create(['locale' => 'de_CH']);

    get(route('de-ch.products.show', ['locale' => 'de_CH', 'product' => $product->slug]))
        ->assertOk()
        ->assertSee($product->name);
})->group('products', 'show');

it('shows a service', function () {
    $service = Service::factory()->create(['locale' => 'de_CH']);

    get(route('de-ch.services.show', ['locale' => 'de_CH', 'service' => $service->slug]))
        ->assertOk()
        ->assertSee($service->name);
})->group('services', 'show');

it('shows a technology', function () {
    $technology = Technology::factory()->create(['locale' => 'de_CH']);

    get(route('de-ch.technologies.show', ['locale' => 'de_CH', 'technology' => $technology->slug]))
        ->assertOk()
        ->assertSee($technology->title);
})->group('technologies', 'show');

it('shows an open source project', function () {
    $openSource = OpenSource::factory()->create(['locale' => 'de_CH']);

    get(route('de-ch.open-source.show', ['locale' => 'de_CH', 'openSource' => $openSource->slug]))
        ->assertOk()
        ->assertSee($openSource->title);
})->group('open-source', 'show');
