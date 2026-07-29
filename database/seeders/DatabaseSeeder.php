<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $user = User::updateOrCreate(
            [
                'email' => 'info@codebar.ch',
            ],
            [
                'name' => 'codebar Solutions AG',
                'password' => bcrypt('password'),
                'locale' => LocaleEnum::EN,
            ]
        );

        $user->markEmailAsVerified();

        $user->assignRole(RoleEnum::ADMINISTRATOR, RoleEnum::USER);

        $this->call(PagesTableSeeder::class);
        $this->call(SeoImageCleanupSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        // OpenSource content comes from `php artisan sync:repositories`, which
        // pulls the live GitHub repositories — the seed data here is stale.
        // $this->call(OpenSourceTableSeeder::class);
        $this->call(TechnologiesTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(AiModelsTableSeeder::class);
        $this->call(NetworksTableSeeder::class);
        $this->call(NetworkUsersTableSeeder::class);
        $this->call(AiModelDailyUsagesTableSeeder::class);

        Artisan::call(ClearCommand::class);
        Artisan::call('responsecache:clear');
    }
}
