<?php

declare(strict_types=1);

use App\Models\News;
use App\Support\LocalizedRouteParameters;
use Illuminate\Support\Facades\App;

it('swaps the locale parameter and the translated slug together', function () {
    App::setLocale('de_CH');

    $news = News::factory()->create([
        'slug' => ['de_CH' => 'deutscher-slug', 'en_CH' => 'english-slug'],
    ]);

    $parameters = LocalizedRouteParameters::for(['locale' => 'de_CH', 'news' => $news], 'en_CH');

    expect($parameters['locale'])->toBe('en_CH')
        ->and($parameters['news'])->toBe('english-slug');
})->group('unit', 'support');

it('keeps the default route key for an article without a translated slug', function () {
    App::setLocale('de_CH');

    $news = News::factory()->create([
        'slug' => ['de_CH' => 'nur-deutsch', 'en_CH' => null],
    ]);

    $parameters = LocalizedRouteParameters::for(['locale' => 'de_CH', 'news' => $news], 'en_CH');

    expect($parameters['news'])->toBe('nur-deutsch');
})->group('unit', 'support');

it('leaves scalar parameters other than the locale untouched', function () {
    $parameters = LocalizedRouteParameters::for(['locale' => 'de_CH', 'page' => 2], 'en_CH');

    expect($parameters)->toBe(['locale' => 'en_CH', 'page' => 2]);
})->group('unit', 'support');
