<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\Codebar\ConfigurationsTableSeeder;
use Database\Seeders\Codebar\ContactsTableSeeder;
use Database\Seeders\Codebar\NewsTableSeeder;
use Database\Seeders\Codebar\OpenSourceTableSeeder;
use Database\Seeders\Codebar\PagesTableSeeder;
use Database\Seeders\Codebar\ProductsTableSeeder;
use Database\Seeders\Codebar\RolesAndPermissionsSeeder;
use Database\Seeders\Codebar\ServicesTableSeeder;
use Database\Seeders\Codebar\TechnologiesTableSeeder;
use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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

        $this->call(ConfigurationsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(OpenSourceTableSeeder::class);
        $this->call(TechnologiesTableSeeder::class);

        if (app()->isLocal()) {
            Artisan::call(ClearCommand::class);
        }

    }
}
