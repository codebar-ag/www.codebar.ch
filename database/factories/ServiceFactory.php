<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
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
        $name = fake()->unique()->words(3, true);

        return [
            'published' => true,
            'locale' => fake()->randomElement(LocaleEnum::cases())->value,
            'group' => fake()->word(),
            'order' => fake()->numberBetween(1, 100),
            'name' => $name,
            'teaser' => fake()->sentence(),
            'slug' => str($name)->slug(),
            'content' => fake()->paragraphs(3, true),
            'image' => fake()->imageUrl(),
            'url' => fake()->url(),
            'tags' => fake()->words(2),
        ];
    }
}
