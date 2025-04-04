<?php

use App\Enums\RoleEnum;
use App\Models\User;

it('renders dashboard.index page', function () {
    $user = User::factory()->create();

    $user->assignRole(RoleEnum::USER);

    $this->actingAs($user)
        ->get(route('dashboard.index'))
        ->assertStatus(200);
})->group('controllers', 'dashboard');
