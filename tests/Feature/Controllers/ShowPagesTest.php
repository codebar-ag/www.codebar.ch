<?php

use App\Enums\LocaleEnum;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

$startRoute = fn () => route(Str::slug(LocaleEnum::DE->value).'.start.index');

it('renders a news article', function () {
    $news = News::factory()->create();

    get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk();
})->group('news', 'show');

it('redirects a product to the start page while products are disabled', function () use ($startRoute) {
    $product = Product::factory()->create();

    get(route('de-ch.products.show', ['locale' => 'de_CH', 'product' => $product->slug]))
        ->assertRedirect($startRoute());
})->group('products', 'show');

it('redirects a service to the start page while service detail pages are disabled', function () use ($startRoute) {
    $service = Service::factory()->create();

    get(route('de-ch.services.show', ['locale' => 'de_CH', 'service' => $service->slug]))
        ->assertRedirect($startRoute());
})->group('services', 'show');

it('redirects a technology to the start page while technologies are disabled', function () use ($startRoute) {
    $technology = Technology::factory()->create();

    get(route('de-ch.technologies.show', ['locale' => 'de_CH', 'technology' => $technology->slug]))
        ->assertRedirect($startRoute());
})->group('technologies', 'show');

it('renders an open source project that has a written body', function () {
    $openSource = OpenSource::factory()->create();

    get(route('de-ch.open-source.show', ['locale' => 'de_CH', 'openSource' => $openSource->slug]))
        ->assertOk();
})->group('open-source', 'show');

it('does not expose an open source project without a written body', function () {
    // sync:repositories imports title and teaser from GitHub but leaves the
    // body empty — there is no page worth serving, let alone indexing.
    $openSource = OpenSource::factory()->create(['content' => null]);

    get(route('de-ch.open-source.show', ['locale' => 'de_CH', 'openSource' => $openSource->slug]))
        ->assertNotFound();
})->group('open-source', 'show');
