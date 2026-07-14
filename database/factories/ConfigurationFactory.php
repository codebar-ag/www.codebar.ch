<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Configuration>
 */
class ConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => fake()->company(),
            'company_primary_color' => fake()->hexColor(),
            'component_intro' => [
                LocaleEnum::DE->value => fake()->paragraph(),
                LocaleEnum::EN->value => fake()->paragraph(),
            ],
            'section_news' => false,
            'section_services' => false,
            'section_products' => false,
            'section_technologies' => false,
            'section_open_source' => false,
            'key' => fake()->unique()->slug(),
            'links' => [],
        ];
    }
}
