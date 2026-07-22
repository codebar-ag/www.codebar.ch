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
                    'title' => 'Hello World! codebar stellt sich vor.',
                    'slug' => 'dhello-world-codebar-stellt-sich-vor',
                    'teaser' => 'Computerprogramme werden vielfach in fernen Ländern entwickelt, nicht aber bei codebar. Hier gibt es «Software made in Basel». Wir haben Sebastian Fix, den Geschäftsführer dieses Start-ups, nach der Idee dahinter gefragt.',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'slug' => 'docuware-7-12-is-here',
                    'teaser' => 'More automation, more insights, more efficiency',
                    'content' => file_get_contents(database_path('files/news/en_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],

                ],
            ]
        );

        $this->seed(
            publishedAt: Carbon::parse('2025-04-06'),
            author: 'Sebastian Bürgin-Fix',
            localizedData: [
                'de_CH' => [
                    'title' => 'DocuWare und codebar Solutions AG: Zwei Partner, eine Mission',
                    'slug' => 'docu-ware-cloud-partner',
                    'teaser' => 'Die codebar Solutions AG ist seit Februar 2023 offizieller Partner der Dokumenten-Management-Lösung (DMS) DocuWare Cloud. Dadurch haben unsere Kund*innen ab sofort ein Tool an der Hand, welches ihnen helfen wird, die Digitalisierung im eigenen Unternehmen voranzutreiben.',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'slug' => 'docuware-7-12-is-here',
                    'teaser' => 'More automation, more insights, more efficiency',
                    'content' => file_get_contents(database_path('files/news/en_CH/20250406_docuware_712.md')),
                    'tags' => ['DMS/ECM', 'DocuWare'],

                ],
            ]
        );

    }

    /**
     * @param  array<string, array<string, mixed>>  $localizedData
     */
    private function seed(Carbon $publishedAt, string $author, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function (array $data, string $locale) use ($author, $publishedAt) {
            $slug = Str::slug(Arr::string($data, 'slug'), '-', $locale);

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
                    'image' => Arr::get($data, 'image', ''),
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
