<?php

use App\Models\ProductModule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('create a ProductModule model', function () {
    $model = ProductModule::factory()->create();

    expect($model)->toBeInstanceOf(ProductModule::class);
})->group('unit', 'models');

test('delete a ProductModule model', function () {
    $model = ProductModule::factory()->create();

    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('uses the slug as route key and belongs to a product', function () {
    $model = new ProductModule;

    expect($model->getRouteKeyName())->toBe('slug')
        ->and($model->product())->toBeInstanceOf(BelongsTo::class);
})->group('unit', 'models');
