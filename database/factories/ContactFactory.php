<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'key' => str($name)->slug()->toString(),
            'published' => true,
            'sort' => fake()->numberBetween(1, 99),
            'name' => $name,
            'sections' => [],
            'image' => fake()->imageUrl(),
            'icons' => [],
        ];
    }
}
