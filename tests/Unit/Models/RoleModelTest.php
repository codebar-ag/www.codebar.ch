<?php

declare(strict_types=1);

use App\Models\Role;

test('create a Role model', function () {
    $model = Role::factory()->create();
    expect($model)->toBeInstanceOf(Role::class);
})->group('unit', 'models');

test('delete a Role model', function () {
    $model = Role::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');
