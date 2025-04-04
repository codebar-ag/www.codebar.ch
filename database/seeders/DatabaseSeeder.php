<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Enums\RoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $user = User::updateOrCreate([
            'name' => 'codebar Solutions AG',
            'email' => 'info@codebar.ch',
            'password' => bcrypt('password'),
            'locale' => LocaleEnum::EN,
        ]);

        $user->markEmailAsVerified();

        $user->assignRole(RoleEnum::ADMINISTRATOR, RoleEnum::USER);
    }
}
