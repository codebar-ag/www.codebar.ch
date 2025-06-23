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
            'logo' => 'layouts._logos._paperflakes',
            'footer' => [
                LocaleEnum::DE->value => 'paperflakes AG',
            ],
            'links' => [
                'linkedin' => 'https://www.linkedin.com/company/paperflakes',
                'github' => 'https://github.com/orgs/paperflakes-ag',
            ],
        ]);
    }
}
