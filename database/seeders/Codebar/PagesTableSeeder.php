<?php

namespace Database\Seeders\Codebar;

use App\Models\Page;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    use ReadsCsv;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->readCsv('pages.csv') as $row) {
            Page::updateOrCreate(
                [
                    'key' => $row['key'],
                    'locale' => $row['locale'],
                ],
                [
                    'robots' => $row['robots'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'image' => $row['image'],
                ]
            );
        }
    }
}
