<?php

namespace Database\Seeders;

use App\Enums\GuardEnum;
use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'name' => RoleEnum::ADMINISTRATOR,
            'guard_name' => GuardEnum::WEB,
        ]);

        Role::updateOrCreate([
            'name' => RoleEnum::USER,
            'guard_name' => GuardEnum::WEB,
        ]);

        Role::updateOrCreate([
            'name' => RoleEnum::API,
            'guard_name' => GuardEnum::WEB,
        ]);
    }
}
