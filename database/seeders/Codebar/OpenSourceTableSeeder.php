<?php

namespace Database\Seeders\Codebar;

use App\Models\OpenSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OpenSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seed(
            sharedSlug: 'laravel-zendesk',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Zendesk',
                    'teaser' => 'Nahtlose Integration von Zendesk-Supportfunktionen in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Zendesk'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Zendesk',
                    'teaser' => 'Seamless integration of Zendesk support features into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Zendesk'],
                ],
            ],
            link: 'https://github.com/codebar-ag/laravel-zendesk',
            downloads: 684,
            version: 'v12.0.1',
        );

    }

    private function seed(string $sharedSlug, array $localizedData, string $link, int $downloads, string $version): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($sharedSlug, $link, $downloads) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return OpenSource::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'title' => Arr::get($data, 'title'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'image' => Arr::get($data, 'image'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                    'link' => $link,
                    'downloads' => $downloads,
                    'version' => $downloads,
                ]
            );
        });

        $entries->each(function (OpenSource $entry) use ($entries) {
            $entries->each(function (OpenSource $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
