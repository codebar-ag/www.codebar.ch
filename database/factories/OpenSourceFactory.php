<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
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
        $title = (string) fake()->unique()->words(3, true);

        return [
            'published' => true,
            'locale' => collect(LocaleEnum::cases())->random()->value,
            'title' => $title,
            'slug' => str($title)->slug(),
            'teaser' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'image' => fake()->imageUrl(),
            'tags' => fake()->words(2),
            'link' => fake()->url(),
            'downloads' => fake()->numberBetween(0, 1000),
            'version' => 'v'.fake()->numerify('#.#.#'),
        ];
    }
}
