<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InfrastructureLayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfrastructureLayer>
 */
class InfrastructureLayerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->lexify('layer????'),
            'order' => fake()->numberBetween(1, 100),
            'color' => '#6F9CC3',
            'name' => ['de_CH' => fake()->word(), 'en_CH' => fake()->word()],
        ];
    }
}
