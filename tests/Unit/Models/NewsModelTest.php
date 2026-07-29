<?php

declare(strict_types=1);

use App\Models\News;

test('create a News model', function () {
    $model = News::factory()->create();
    expect($model)->toBeInstanceOf(News::class);
})->group('unit', 'models');

test('delete a News model', function () {
    $model = News::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = News::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('translates title, teaser and content per locale', function () {
    $news = News::factory()->create([
        'title' => ['de_CH' => 'Titel DE', 'en_CH' => 'Title EN'],
        'teaser' => ['de_CH' => 'Teaser DE', 'en_CH' => 'Teaser EN'],
    ]);

    expect($news->getTranslation('title', 'de_CH'))->toBe('Titel DE')
        ->and($news->getTranslation('title', 'en_CH'))->toBe('Title EN')
        ->and($news->getTranslation('teaser', 'de_CH'))->toBe('Teaser DE')
        ->and($news->getTranslation('teaser', 'en_CH'))->toBe('Teaser EN');
})->group('unit', 'models');
