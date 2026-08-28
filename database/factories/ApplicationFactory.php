<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApplicationStatusEnum;
use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_key' => Application::JOB_KEY_INTERNSHIP,
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'age' => fake()->numberBetween(15, 22),
            'city' => fake()->city(),
            'interests' => fake()->paragraph(),
            'focus_fit' => fake()->paragraph(),
            'built_so_far' => fake()->paragraph(),
            'about' => fake()->paragraph(),
            'github' => 'https://github.com/'.fake()->userName(),
            'linkedin' => 'https://www.linkedin.com/in/'.fake()->userName(),
            'project_link' => fake()->url(),
            'status' => ApplicationStatusEnum::Draft,
            'submitted_at' => null,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (): array => [
            'status' => ApplicationStatusEnum::Submitted,
            'submitted_at' => now(),
        ]);
    }

    public function empty(): static
    {
        return $this->state(fn (): array => [
            'first_name' => null,
            'last_name' => null,
            'age' => null,
            'city' => null,
            'interests' => null,
            'focus_fit' => null,
            'built_so_far' => null,
            'about' => null,
            'github' => null,
            'linkedin' => null,
            'project_link' => null,
        ]);
    }
}
