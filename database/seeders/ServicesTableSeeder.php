<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Services live as markdown files with YAML front matter under
 * database/files/services/{locale}/. Adding one means adding two files — one per
 * language — and running `php artisan services:import`; no code change is needed.
 *
 * Only services that genuinely exist in both languages are imported.
 */
class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('services:import', [], $this->command->getOutput());
    }
}
