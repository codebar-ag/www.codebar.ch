<?php

use App\Models\User;

it('get activity log options', function () {
    $model = User::factory()->create();

    expect($model->getActivitylogOptions())->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
})->group('unit', 'traits', 'activity');

it('get activity log subjects', function () {
    $model = User::factory()->create();

    expect($model->subjects())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphMany::class);
})->group('unit', 'traits', 'activity');
