<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::updateOrCreate(
            [
                'name' => 'DMS/ECM',
                'slug' => 'dms-ecm',
            ],
            [
                'order' => 1,
                'content' => null,
            ]);

        Service::updateOrCreate(
            [
                'name' => 'zunscan.ch',
                'slug' => 'zunscan-ch',
            ],
            [
                'order' => 2,
                'content' => null,
                'url' => 'https://zunscan.paperflakes.ch',
            ]);
    }
}
