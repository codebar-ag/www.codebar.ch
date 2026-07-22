<?php

use App\Models\Product;

test('create a Product model', function () {
    $model = Product::factory()->create();
    expect($model)->toBeInstanceOf(Product::class);
})->group('unit', 'models');

test('delete a Product model', function () {
    $model = Product::factory()->create();
    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = Product::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('has a references relation', function () {
    $product = Product::factory()->create();
    $other = Product::factory()->create();

    $product->references()->create([
        'reference_type' => Product::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($product->references()->count())->toBe(1);
    expect($product->references->first()->target)->toBeInstanceOf(Product::class);
})->group('unit', 'models');
