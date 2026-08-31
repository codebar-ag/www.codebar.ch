<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Job positions live as one YAML file per position under database/files/jobs/.
 * Opening, closing or adding a position means editing that file and running
 * `php artisan jobs:import`; no code change is needed.
 */
class JobPositionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('jobs:import', [], $this->command->getOutput());
    }
}
