<?php

namespace Database\Seeders;

use Database\Seeders\Codebar\ConfigurationsTableSeeder;
use Database\Seeders\Codebar\ContactsTableSeeder;
use Database\Seeders\Codebar\OpenSourceTableSeeder;
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
        $this->call(ConfigurationsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        // $this->call(OpenSourceTableSeeder::class);
        $this->call(TechnologiesTableSeeder::class);

        if (app()->isLocal()) {
            Artisan::call(ClearCommand::class);
        }

    }
}
