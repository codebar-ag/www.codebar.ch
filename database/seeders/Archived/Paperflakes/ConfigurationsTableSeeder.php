<?php

namespace Database\Seeders\Archived\Paperflakes;

use App\Enums\LocaleEnum;
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
            'company_primary_color' => '#69b3a1',

            'component_intro' => [
                LocaleEnum::DE->value => file_get_contents(database_path('files/intro/paperflakes_intro_de.md')),
                LocaleEnum::EN->value => file_get_contents(database_path('files/intro/paperflakes_intro_en.md')),
            ],

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
