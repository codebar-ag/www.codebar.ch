<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Articles live as markdown files with YAML front matter under
 * database/files/news/{locale}/. Adding one means adding two files — one per language —
 * and running `php artisan news:import`; no code change is needed any more.
 *
 * Only articles that genuinely exist in both languages are imported. A pair where one
 * side is missing is reported and skipped: publishing it would produce an hreflang pair
 * joining two different articles, which is exactly the wrong-language signal that costs
 * indexing across a whole domain.
 */
class NewsTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('news:import', [], $this->command->getOutput());
    }
}
