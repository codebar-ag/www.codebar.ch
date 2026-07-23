<?php

namespace Database\Seeders;

use App\Models\Technology;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class TechnologiesTableSeeder extends Seeder
{
    use ReadsCsv;

    public function run(): void
    {
        collect($this->readCsv('technologies.csv'))
            ->groupBy('slug')
            ->each(function ($rows, string $slug) {
                $byLocale = $rows->keyBy('locale');
                $first = $rows->first() ?? [];

                Technology::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'published' => filter_var($first['published'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'group' => $first['group'] ?? '',
                        'order' => (int) ($first['order'] ?? 0),
                        'title' => $byLocale->map(fn (array $row) => $row['title'])->all(),
                        'teaser' => $byLocale->map(fn (array $row) => $row['teaser'])->all(),
                        'content' => $byLocale->map(fn (array $row) => $row['content'] !== '' ? $row['content'] : null)->all(),
                        'image' => $first['image'] ?? '',
                        'tags' => $this->decodeJson($first['tags'] ?? ''),
                        'link' => $first['link'] ?? null,
                    ]
                );
            });
    }
}
