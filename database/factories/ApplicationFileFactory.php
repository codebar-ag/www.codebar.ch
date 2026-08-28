<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ApplicationFile>
 */
class ApplicationFileFactory extends Factory
{
    protected $model = ApplicationFile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uuid = (string) Str::uuid();

        return [
            'application_id' => Application::factory(),
            'uuid' => $uuid,
            'disk' => 's3',
            'path' => 'applications/documents/'.$uuid.'.pdf',
            'original_name' => fake()->word().'.pdf',
            'mime' => 'application/pdf',
            'size' => fake()->numberBetween(10_000, 5_000_000),
        ];
    }
}
