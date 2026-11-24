<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InfrastructureStack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfrastructureStack>
 */
class InfrastructureStackFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => 'web.'.fake()->unique()->lexify('??????'),
            'is_default' => false,
            'name' => fake()->company(),
            'domain' => fake()->domainName(),
            'image' => null,
            'sketch' => null,
            'services' => [],
            'overrides' => [],
        ];
    }
}
