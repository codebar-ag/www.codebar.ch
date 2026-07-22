<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Network>
 */
class NetworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'key' => Str::slug($name),
            'locale' => LocaleEnum::DE->value,
            'name' => $name,
            'category' => $this->faker->randomElement(NetworkCategoryEnum::cases())->value,
            'status' => NetworkStatusEnum::ACTIVE->value,
            'cover_url' => null,
            'tier_label' => null,
            'excerpt' => null,
            'website' => null,
            'since_year' => null,
            'until_year' => null,
            'page_slug' => null,
            'published' => true,
            'sort' => 0,
        ];
    }
}
