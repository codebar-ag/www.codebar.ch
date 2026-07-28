<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class NewsTableSeeder extends Seeder
{
    /**
     * Only articles that genuinely exist in both languages belong here.
     *
     * This seeder previously defined two entries that both read the same
     * markdown file, and one of them paired a German title with an unrelated
     * English one. Published, that would have produced two URLs with identical
     * bodies plus an hreflang pair joining two different articles — exactly the
     * duplicate-content and wrong-language signals that cost indexing across a
     * whole domain. Add the second article back once its text has been written.
     */
    public function run(): void
    {
        $this->seed(
            slug: 'docuware-7-12-is-here',
            publishedAt: Carbon::parse('2025-04-06'),
            author: 'Sebastian Bürgin-Fix',
            localizedData: [
                'de_CH' => [
                    'title' => 'DocuWare 7.12 ist da',
                    'teaser' => 'Mehr Automatisierung, mehr Einblick, mehr Effizienz: Das Release verbessert die E-Rechnungsverarbeitung, bringt IDP in die Cloud-Konfiguration und öffnet Workflow-Daten für Analytics.',
                    'content' => file_get_contents(database_path('files/news/de_CH/20250406_docuware_712.md')),
                ],
                'en_CH' => [
                    'title' => 'DocuWare 7.12 is here',
                    'teaser' => 'More automation, more insight, more efficiency: this release improves e-invoice processing, brings IDP into the cloud configuration and opens workflow data up to analytics.',
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
