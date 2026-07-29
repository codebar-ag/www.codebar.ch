<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Technologies live as markdown files with YAML front matter under
 * database/files/technologies/{locale}/. Adding one means adding two files — one per
 * language — and running `php artisan technologies:import`; no code change is needed.
 */
class TechnologiesTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('technologies:import', [], $this->command->getOutput());
    }
}
