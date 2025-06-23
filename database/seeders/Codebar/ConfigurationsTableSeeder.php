<?php

namespace Database\Seeders\Codebar;

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

            'section_services' => false,
            'section_products' => false,
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
