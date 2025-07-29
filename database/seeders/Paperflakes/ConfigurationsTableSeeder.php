<?php

namespace Database\Seeders\Paperflakes;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuration::updateOrCreate([], [
            'company' => 'paperflakes AG',

            'section_news' => true,
            'section_services' => true,
            'section_products' => true,
            'section_technologies' => false,
            'section_open_source' => false,

            'key' => '_paperflakes',

            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/paperflakes',
                'github' => 'https://github.com/orgs/paperflakes-ag',
            ],

        ]);
    }
}
