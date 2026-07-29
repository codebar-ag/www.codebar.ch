<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var string $nameDe words() returns a string when $asText is true */
        $nameDe = fake()->unique()->words(3, true);
        /** @var string $nameEn words() returns a string when $asText is true */
        $nameEn = fake()->unique()->words(3, true);

        return [
            'published' => true,
            'group' => fake()->word(),
            'order' => fake()->numberBetween(1, 100),
            'name' => ['de_CH' => $nameDe, 'en_CH' => $nameEn],
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'slug' => str($nameDe)->slug(),
            'content' => ['de_CH' => fake()->paragraphs(3, true), 'en_CH' => fake()->paragraphs(3, true)],
            'image' => fake()->imageUrl(),
            'url' => fake()->url(),
            'tags' => fake()->words(2),
        ];
    }
}
