<?php

namespace Database\Seeders\Codebar;

use App\Models\Technology;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class TechnologiesTableSeeder extends Seeder
{
    use ReadsCsv;

    public function run(): void
    {
        $entries = collect($this->readCsv('technologies.csv'))->map(function (array $row) {
            return Technology::updateOrCreate(
                [
                    'locale' => $row['locale'],
                    'slug' => $row['slug'],
                ],
                [
                    'published' => filter_var($row['published'], FILTER_VALIDATE_BOOLEAN),
                    'group' => $row['group'],
                    'order' => (int) $row['order'],
                    'title' => $row['title'],
                    'teaser' => $row['teaser'],
                    'content' => $row['content'] !== '' ? $row['content'] : null,
                    'image' => $row['image'],
                    'tags' => $this->decodeJson($row['tags']),
                    'link' => $row['link'],
                ]
            );
        });

        $entries->groupBy('slug')->each(function ($group) {
            $group->each(function (Technology $entry) use ($group) {
                $group->each(function (Technology $reference) use ($entry) {
                    $entry->references()->updateOrCreate([
                        'reference_type' => get_class($reference),
                        'reference_id' => $reference->id,
                        'reference_locale' => $reference->locale,
                    ]);
                });
            });
        });
    }
}
