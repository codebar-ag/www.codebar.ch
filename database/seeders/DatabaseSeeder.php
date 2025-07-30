<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\Paperflakes\RolesAndPermissionsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

        if (Config::get('seeder.seeder.paperflakes')) {
            // php artisan db:seed --class=Database\\Seeders\\PaperflakesSeeder --force
            $this->call(PaperflakesSeeder::class);
        }
        if (Config::get('seeder.seeder.codebar')) {
            // php artisan db:seed --class=Database\\Seeders\\CodebarSeeder --force
            $this->call(CodebarSeeder::class);
        }

    }
}
