<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Products live as markdown files with YAML front matter under
 * database/files/products/{locale}/. Adding one means adding two files — one per
 * language — and running `php artisan products:import`; no code change is needed.
 */
class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('products:import', [], $this->command->getOutput());
    }
}
