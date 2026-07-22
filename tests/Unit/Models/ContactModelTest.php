<?php

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;

test('create a Contact model', function () {
    $model = Contact::factory()->create();
    expect($model)->toBeInstanceOf(Contact::class);
})->group('unit', 'models');

test('delete a Contact model', function () {
    $model = Contact::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('clears the published cache when a contact is saved or deleted', function () {
    Cache::shouldReceive('forget')->atLeast()->once();

    Contact::factory()->create();
})->group('unit', 'models');
