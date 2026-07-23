<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->seed(
            slug: 'docuware-7-12-is-here',
            publishedAt: Carbon::parse('2025-04-06'),
            author: 'Sebastian Bürgin-Fix',
            localizedData: [
                'de_CH' => [
                    'title' => 'Hello World! codebar stellt sich vor.',
                    'teaser' => 'Computerprogramme werden vielfach in fernen Ländern entwickelt, nicht aber bei codebar. Hier gibt es «Software made in Basel». Wir haben Sebastian Fix, den Geschäftsführer dieses Start-ups, nach der Idee dahinter gefragt.',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'teaser' => 'More automation, more insights, more efficiency',
                    'content' => file_get_contents(database_path('files/news/en_CH/20250406_docuware_712.md')),
                ],
            ],
            tags: ['DMS/ECM', 'DocuWare'],
        );

        $this->seed(
            slug: 'docu-ware-cloud-partner',
            publishedAt: Carbon::parse('2025-04-06'),
            author: 'Sebastian Bürgin-Fix',
            localizedData: [
                'de_CH' => [
                    'title' => 'DocuWare und codebar Solutions AG: Zwei Partner, eine Mission',
                    'teaser' => 'Die codebar Solutions AG ist seit Februar 2023 offizieller Partner der Dokumenten-Management-Lösung (DMS) DocuWare Cloud. Dadurch haben unsere Kund*innen ab sofort ein Tool an der Hand, welches ihnen helfen wird, die Digitalisierung im eigenen Unternehmen voranzutreiben.',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'teaser' => 'More automation, more insights, more efficiency',
                    'content' => file_get_contents(database_path('files/news/en_CH/20250406_docuware_712.md')),
                ],
            ],
            tags: ['DMS/ECM', 'DocuWare'],
        );

    }

    /**
     * @param  array<string, array<string, mixed>>  $localizedData
     * @param  array<int, string>  $tags
     */
    private function seed(string $slug, Carbon $publishedAt, string $author, array $localizedData, array $tags = []): void
    {
        News::updateOrCreate(
            ['slug' => $slug],
            [
                'author' => $author,
                'published_at' => $publishedAt,
                'title' => collect($localizedData)->map(fn (array $data) => Arr::get($data, 'title'))->all(),
                'teaser' => collect($localizedData)->map(fn (array $data) => Arr::get($data, 'teaser'))->all(),
                'content' => collect($localizedData)->map(fn (array $data) => Arr::get($data, 'content'))->all(),
                'image' => Arr::get(collect($localizedData)->first() ?? [], 'image', ''),
                'tags' => $tags,
            ]
        );
    }
}
