<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
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
        $title = fake()->unique()->sentence();

        return [
            'locale' => fake()->randomElement(LocaleEnum::cases())->value,
            'title' => $title,
            'slug' => str($title)->slug(),
            'teaser' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'image' => fake()->imageUrl(),
            'published_at' => fake()->dateTime(),
            'author' => fake()->name(),
            'tags' => fake()->words(2),
        ];
    }
}
