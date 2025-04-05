<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::updateOrCreate(
            [
                'title' => 'Hello World!',
                'slug' => 'hello-world',
            ],
            [
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'published_at' => now(),
                'content' => null,
            ]);
    }
}
