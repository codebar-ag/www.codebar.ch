<?php

use App\Models\News;

test('create a News model', function () {
    $model = News::factory()->create();
    expect($model)->toBeInstanceOf(News::class);
})->group('unit', 'models');

test('delete a News model', function () {
    $model = News::factory()->create();
    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = News::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('has a references relation', function () {
    $news = News::factory()->create();
    $other = News::factory()->create();

    $news->references()->create([
        'reference_type' => News::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($news->references()->count())->toBe(1);
    expect($news->references->first()->target)->toBeInstanceOf(News::class);
})->group('unit', 'models');
