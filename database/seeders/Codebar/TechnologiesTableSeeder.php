<?php

namespace Database\Seeders\Codebar;

use App\Enums\LocaleEnum;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TechnologiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            order: 1,
            sharedSlug: 'laravel-framework',
            group: 'Backend',
            localizedData: [
                LocaleEnum::DE->value => [
                    'title' => 'Laravel',
                    'teaser' => '',
                    'tags' => ['PHP'],
                    'content' => null,
                ],
                LocaleEnum::EN->value => [
                    'title' => 'Laravel',
                    'teaser' => '',
                    'tags' => ['PHP'],
                    'content' => null,
                ],
            ],
            link: 'https://laravel.com/',
        );

    }

    private function seed(int $order, string $sharedSlug, string $group, array $localizedData, string $link): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($sharedSlug, $order, $group, $link) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return Technology::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'order' => $order,
                    'group' => $group,
                    'title' => Arr::get($data, 'title'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                    'image' => Arr::get($data, 'image', ''),
                    'link' => $link,
                ]
            );
        });

        $entries->each(function (Technology $entry) use ($entries) {
            $entries->each(function (Technology $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
