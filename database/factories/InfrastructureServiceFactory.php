<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InfrastructureOperationEnum;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfrastructureService>
 */
class InfrastructureServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'infrastructure_layer_id' => InfrastructureLayer::factory(),
            'key' => fake()->unique()->lexify('service????'),
            'order' => fake()->numberBetween(1, 100),
            'name' => ['de_CH' => fake()->word(), 'en_CH' => fake()->word()],
            'text' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'provider' => fake()->company(),
            'vendor' => fake()->company(),
            'vendor_country' => 'CH',
            'place' => '',
            'countries' => ['CH'],
            'operation' => InfrastructureOperationEnum::SERVICE,
            'links' => [['type' => 'website', 'url' => 'https://example.com']],
        ];
    }
}
