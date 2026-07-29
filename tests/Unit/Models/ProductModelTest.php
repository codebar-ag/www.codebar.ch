<?php

declare(strict_types=1);

use App\Models\Product;

test('create a Product model', function () {
    $model = Product::factory()->create();
    expect($model)->toBeInstanceOf(Product::class);
})->group('unit', 'models');

test('delete a Product model', function () {
    $model = Product::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = Product::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('translates name, teaser and nested features per locale', function () {
    $product = Product::factory()->create([
        'name' => ['de_CH' => 'Name DE', 'en_CH' => 'Name EN'],
        'features' => [
            'de_CH' => [['title' => 'Titel DE', 'description' => 'Beschreibung DE']],
            'en_CH' => [['title' => 'Title EN', 'description' => 'Description EN']],
        ],
    ]);

    expect($product->getTranslation('name', 'de_CH'))->toBe('Name DE')
        ->and($product->getTranslation('name', 'en_CH'))->toBe('Name EN')
        ->and($product->getTranslation('features', 'de_CH'))->toBe([['title' => 'Titel DE', 'description' => 'Beschreibung DE']])
        ->and($product->getTranslation('features', 'en_CH'))->toBe([['title' => 'Title EN', 'description' => 'Description EN']]);
})->group('unit', 'models');
