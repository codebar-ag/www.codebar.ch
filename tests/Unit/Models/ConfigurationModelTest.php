<?php

use App\Models\Configuration;

test('create a Configuration model', function () {
    $model = Configuration::factory()->create();
    expect($model)->toBeInstanceOf(Configuration::class);
})->group('unit', 'models');

test('delete a Configuration model', function () {
    $model = Configuration::factory()->create();
    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('casts the section flags to booleans', function () {
    $model = Configuration::factory()->create(['section_news' => true]);

    expect($model->section_news)->toBeTrue();
    expect($model->section_services)->toBeFalse();
})->group('unit', 'models');
