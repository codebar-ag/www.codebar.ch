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
            'section_services' => false,
            'section_products' => false,
            'section_technologies' => true,
            'section_open_source' => true,

            'logo' => 'layouts._logos._codebar',

            'contact' => null,
            'terms' => null,
            'imprint' => null,
            'privacy' => null,

            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/codebarag',
                'github' => 'https://github.com/orgs/codebar-ag',
            ],

            'footer' => [
                LocaleEnum::DE->value => 'codebar Solutions AG',
            ],

        ]);
    }
}
