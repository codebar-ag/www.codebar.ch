<?php

namespace Database\Factories;

use App\Models\NetworkUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NetworkUser>
 */
class NetworkUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'network_key' => Str::slug($this->faker->company()),
            'name' => $this->faker->name(),
            'role' => null,
            'avatar_disk' => null,
            'avatar_path' => null,
            'avatar_url' => null,
            'email' => $this->faker->unique()->safeEmail(),
            'linkedin' => null,
            'phone' => null,
            'published' => true,
            'sort' => 0,
        ];
    }
}
