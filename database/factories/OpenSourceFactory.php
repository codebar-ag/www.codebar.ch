<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OpenSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OpenSource>
 */
class OpenSourceFactory extends Factory
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
            'title' => ['de_CH' => $titleDe, 'en_CH' => $titleEn],
            'slug' => str($titleDe)->slug(),
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'content' => ['de_CH' => fake()->paragraphs(3, true), 'en_CH' => fake()->paragraphs(3, true)],
            'image' => fake()->imageUrl(),
            'tags' => fake()->words(2),
            'link' => fake()->url(),
            'downloads' => fake()->numberBetween(0, 1000),
            'version' => 'v'.fake()->numerify('#.#.#'),
        ];
    }
}
