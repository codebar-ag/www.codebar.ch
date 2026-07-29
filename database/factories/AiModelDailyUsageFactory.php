<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AiModelDailyUsage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiModelDailyUsage>
 */
class AiModelDailyUsageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $promptTokens = fake()->numberBetween(1_000, 5_000_000);
        $completionTokens = fake()->numberBetween(1_000, 1_000_000);

        return [
            'date' => fake()->unique()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'model' => fake()->randomElement(['qwen3.6:35b', 'qwen3-coder:30b', 'qwen3-embedding:4b', 'gemma4:31b']),
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
            'total_tokens' => $promptTokens + $completionTokens,
            'requests' => fake()->numberBetween(1, 2_000),
            'spend' => fake()->randomFloat(6, 0, 10),
        ];
    }
}
