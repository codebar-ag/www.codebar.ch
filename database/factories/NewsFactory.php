<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleDe = fake()->unique()->sentence();
        $titleEn = fake()->unique()->sentence();

        return [
            'title' => ['de_CH' => $titleDe, 'en_CH' => $titleEn],
            'slug' => str($titleDe)->slug(),
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'content' => ['de_CH' => fake()->paragraphs(3, true), 'en_CH' => fake()->paragraphs(3, true)],
            'image' => fake()->imageUrl(),
            'published_at' => fake()->dateTime(),
            'author' => fake()->name(),
            'tags' => fake()->words(2),
        ];
    }
}
