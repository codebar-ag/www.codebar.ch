<?php

use App\Models\News;
use App\Models\Reference;

test('create and delete a Reference model', function () {
    $news = News::factory()->create();
    $other = News::factory()->create();

    $reference = Reference::create([
        'source_type' => News::class,
        'source_id' => $news->id,
        'reference_type' => News::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($reference)->toBeInstanceOf(Reference::class);
    expect($reference->source)->toBeInstanceOf(News::class);
    expect($reference->source?->is($news))->toBeTrue();
    expect($reference->target)->toBeInstanceOf(News::class);
    expect($reference->target?->is($other))->toBeTrue();

    expect($reference->delete())->toBeTrue();
})->group('unit', 'models');
