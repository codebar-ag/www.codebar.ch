<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition()
    {
        return [
            'log_name' => $this->faker->colorName(),
            'description' => $this->faker->text(),
            'subject_type' => User::class,
            'event' => $this->faker->colorName(),
            'subject_id' => User::factory(),
            'causer_type' => User::class,
            'causer_id' => User::factory(),
        ];
    }
}
