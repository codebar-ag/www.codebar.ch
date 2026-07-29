<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Page SEO metadata lives as one YAML file per page under database/files/pages/.
 * Adding or changing a page means editing that file and running
 * `php artisan pages:import`; no code change is needed.
 *
 * This replaced a CSV that carried JSON inside its cells, plus a second,
 * duplicate list of "upcoming" pages hardcoded here — both unreadable in a diff.
 */
class PagesTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('pages:import', [], $this->command->getOutput());
    }
}
