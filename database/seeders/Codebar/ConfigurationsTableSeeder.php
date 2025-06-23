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
            'logo' => 'layouts._logos._codebar',
            'footer' => [
                LocaleEnum::DE->value => 'codebar Solutions AG',
            ],
            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/codebarag',
                'github' => 'https://github.com/orgs/codebar-ag',
            ],
        ]);
    }
}
