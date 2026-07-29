<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * The AI model catalogue lives as one YAML file per model under
 * database/files/ai_models/. Adding or changing one means editing that file and
 * running `php artisan ai-models:import`; no code change is needed.
 */
class AiModelsTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('ai-models:import', [], $this->command->getOutput());
    }
}
