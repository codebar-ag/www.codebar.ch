<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\JobPositionStatusEnum;
use App\Models\JobPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobPosition>
 */
class JobPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->jobTitle();

        return [
            'key' => str($title)->slug()->toString(),
            'published' => true,
            'sort' => fake()->numberBetween(1, 99),
            'status' => JobPositionStatusEnum::Open,
            'route_name' => null,
            'title' => ['de_CH' => $title, 'en_CH' => $title],
            'teaser' => ['de_CH' => fake()->sentence(), 'en_CH' => fake()->sentence()],
        ];
    }
}
