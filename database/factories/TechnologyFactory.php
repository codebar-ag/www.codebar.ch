<?php

namespace Database\Factories;

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
        /** @var string $titleDe words() returns a string when $asText is true */
        $titleDe = fake()->unique()->words(3, true);
        /** @var string $titleEn words() returns a string when $asText is true */
        $titleEn = fake()->unique()->words(3, true);

        return [
            'published' => true,
            'group' => fake()->word(),
            'order' => fake()->numberBetween(1, 100),
            'title' => ['de_CH' => $titleDe, 'en_CH' => $titleEn],
            'slug' => str($titleDe)->slug(),
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'content' => ['de_CH' => fake()->paragraphs(3, true), 'en_CH' => fake()->paragraphs(3, true)],
            'image' => fake()->imageUrl(),
            'tags' => fake()->words(2),
            'link' => fake()->url(),
        ];
    }
}
