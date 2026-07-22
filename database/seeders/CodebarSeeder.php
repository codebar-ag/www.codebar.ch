<?php

namespace Database\Seeders;

use Database\Seeders\Codebar\AiModelDailyUsagesTableSeeder;
use Database\Seeders\Codebar\AiModelsTableSeeder;
use Database\Seeders\Codebar\ContactsTableSeeder;
use Database\Seeders\Codebar\NetworksTableSeeder;
use Database\Seeders\Codebar\NetworkUsersTableSeeder;
use Database\Seeders\Codebar\OpenSourceTableSeeder;
use Database\Seeders\Codebar\PagesTableSeeder;
use Database\Seeders\Codebar\ProductsTableSeeder;
use Database\Seeders\Codebar\TechnologiesTableSeeder;
use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CodebarSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PagesTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        // $this->call(OpenSourceTableSeeder::class);
        $this->call(TechnologiesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(AiModelsTableSeeder::class);
        $this->call(NetworksTableSeeder::class);
        $this->call(NetworkUsersTableSeeder::class);

        if (app()->isLocal()) {
            $this->call(AiModelDailyUsagesTableSeeder::class);

            Artisan::call(ClearCommand::class);
        }

    }
}
