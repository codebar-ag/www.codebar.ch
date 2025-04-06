<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = now();
        $now_formatted = $now->format('Ymd');

        $locale = LocaleEnum::DE->value;

        News::updateOrCreate(
            [
                'title' => 'DocuWare 7.12 ist da',
                'slug' => Str::slug("docuware-7-12-ist-da-{$now_formatted}-{$locale}"),
            ],
            [
                'teaser' => 'Mehr Automatisierung, mehr Insights, mehr Effizienz',
                'locale' => LocaleEnum::DE->value,
                'published_at' => $now,
                'content' => file_get_contents(database_path("files/news/{$locale}/20250406_docuware_712.md")),
            ]);

        $locale = LocaleEnum::EN->value;

        News::updateOrCreate(
            [
                'title' => 'DocuWare 7.12 is here',
                'slug' => Str::slug("docuware-7-12-ist-here-{$now_formatted}-{$locale}"),
            ],
            [
                'teaser' => 'More automation, more insights, more efficiency',
                'locale' => LocaleEnum::EN->value,
                'published_at' => $now,
                'content' => file_get_contents(database_path("files/news/{$locale}/20250406_docuware_712.md")),
            ]);
    }
}
