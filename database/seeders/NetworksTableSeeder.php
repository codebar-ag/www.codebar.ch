<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Network partners live as one YAML file per company under database/files/networks/.
 * Adding or changing one means editing that file and running
 * `php artisan networks:import`; no code change is needed.
 */
class NetworksTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('networks:import', [], $this->command->getOutput());
    }
}
