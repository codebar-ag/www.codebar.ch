<?php

namespace Database\Seeders\Codebar;

use App\Models\Configuration;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class ConfigurationsTableSeeder extends Seeder
{
    use ReadsCsv;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->readCsv('configurations.csv') as $row) {
            Configuration::updateOrCreate(
                ['key' => $row['key']],
                [
                    'company' => $row['company'],
                    'company_primary_color' => $row['company_primary_color'],
                    'component_intro' => $this->decodeJson($row['component_intro']),
                    'section_news' => filter_var($row['section_news'], FILTER_VALIDATE_BOOLEAN),
                    'section_services' => filter_var($row['section_services'], FILTER_VALIDATE_BOOLEAN),
                    'section_products' => filter_var($row['section_products'], FILTER_VALIDATE_BOOLEAN),
                    'section_technologies' => filter_var($row['section_technologies'], FILTER_VALIDATE_BOOLEAN),
                    'section_open_source' => filter_var($row['section_open_source'], FILTER_VALIDATE_BOOLEAN),
                    'links' => $this->decodeJson($row['links']),
                ]
            );
        }
    }
}
