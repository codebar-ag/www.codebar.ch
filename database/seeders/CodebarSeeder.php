<?php

namespace Database\Seeders;

use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CodebarSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            Artisan::call(ClearCommand::class);
        }

    }
}
