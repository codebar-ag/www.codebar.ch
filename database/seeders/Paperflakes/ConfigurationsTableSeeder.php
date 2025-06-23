<?php

namespace Database\Seeders\Paperflakes;

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
            'section_services' => true,
            'section_products' => true,
            'section_technologies' => false,
            'section_open_source' => false,

            'logo' => 'layouts._logos._paperflakes',

            'contact' => null,
            'terms' => null,
            'imprint' => null,
            'privacy' => null,

            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/paperflakes',
                'github' => 'https://github.com/orgs/paperflakes-ag',
            ],
            'footer' => [
                LocaleEnum::DE->value => 'paperflakes AG',
            ],
        ]);
    }
}
