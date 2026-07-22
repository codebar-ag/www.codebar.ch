<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Technology>
 */
class TechnologyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var string $title words() returns a string when $asText is true */
        $title = fake()->unique()->words(3, true);

        return [
            'published' => true,
            'locale' => collect(LocaleEnum::cases())->random()->value,
            'group' => fake()->word(),
            'order' => fake()->numberBetween(1, 100),
            'title' => $title,
            'slug' => str($title)->slug(),
            'teaser' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'image' => fake()->imageUrl(),
            'tags' => fake()->words(2),
            'link' => fake()->url(),
        ];
    }
}
