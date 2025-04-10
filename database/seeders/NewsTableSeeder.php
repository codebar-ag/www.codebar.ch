<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seed(
            publishedAt: Carbon::parse('2025-04-06'),
            author: 'Sebastian Bürgin-Fix',
            localizedData: [
                'de_CH' => [
                    'title' => 'DocuWare 7.12 ist da',
                    'slug' => 'docuware-7-12-ist-da',
                    'teaser' => 'Mehr Automatisierung, mehr Insights, mehr Effizienz',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'slug' => 'docuware-7-12-is-here',
                    'teaser' => 'More automation, more insights, more efficiency',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/news/en_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],

                ],
            ]
        );
    }

    private function seed(Carbon $publishedAt, string $author, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($author, $publishedAt) {
            $slug = Str::slug(Arr::get($data, 'slug'), '-', $locale);

            return News::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'author' => $author,
                    'published_at' => $publishedAt,
                    'title' => Arr::get($data, 'title'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'image' => Arr::get($data, 'image'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                ]
            );
        });

        $entries->each(function (News $entry) use ($entries) {
            $entries->each(function (News $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
