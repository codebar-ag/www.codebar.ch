<?php

declare(strict_types=1);

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
            'key' => str($titleDe)->slug()->toString(),
            'title' => ['de_CH' => $titleDe, 'en_CH' => $titleEn],
            'slug' => [
                'de_CH' => str($titleDe)->slug()->toString(),
                'en_CH' => str($titleEn)->slug()->toString(),
            ],
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
            'content' => ['de_CH' => fake()->paragraphs(3, true), 'en_CH' => fake()->paragraphs(3, true)],
            'hero_image' => null,
            'published_at' => fake()->dateTime(),
            'published' => true,
            'author' => fake()->name(),
            'tags' => fake()->words(2),
            'reading_minutes' => fake()->numberBetween(2, 12),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (): array => ['published' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['featured' => true]);
    }
}
