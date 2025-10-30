<?php

namespace Database\Seeders\Codebar;

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

            'company' => 'codebar Solutions AG',
            'company_primary_color' => '#500472',

            'component_intro' => [
                LocaleEnum::DE->value => file_get_contents(database_path('files/intro/codebar_intro_de.md')),
                LocaleEnum::EN->value => file_get_contents(database_path('files/intro/codebar_intro_en.md')),
            ],

            'section_news' => true,
            'section_services' => true,
            'section_products' => true,
            'section_technologies' => true,
            'section_open_source' => true,

            'key' => '_codebar',

            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/codebarag',
                'github' => 'https://github.com/orgs/codebar-ag',
            ],
        ]);
    }
}
