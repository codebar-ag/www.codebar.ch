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
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'published_at' => now(),
                'content' => null,
            ]);
    }
}
