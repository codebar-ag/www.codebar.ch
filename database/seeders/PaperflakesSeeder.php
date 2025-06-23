<?php

namespace Database\Seeders;

use Database\Seeders\Paperflakes\ConfigurationsTableSeeder;
use Database\Seeders\Paperflakes\ContactsTableSeeder;
use Database\Seeders\Paperflakes\NewsTableSeeder;
use Database\Seeders\Paperflakes\PagesTableSeeder;
use Database\Seeders\Paperflakes\ProductsTableSeeder;
use Database\Seeders\Paperflakes\ServicesTableSeeder;
use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PaperflakesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ConfigurationsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(ContactsTableSeeder::class);

        if (app()->isLocal()) {
            Artisan::call(ClearCommand::class);
        }

    }
}
