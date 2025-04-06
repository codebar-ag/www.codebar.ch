<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $publishedAt = Carbon::parse('2025-04-06 00:00:00');
        $publishedAtFormatted = $publishedAt->format('Ymd');

        $locale = LocaleEnum::DE->value;

        News::updateOrCreate(
            [
                'title' => 'DocuWare 7.12 ist da',
                'slug' => Str::slug("docuware-7-12-ist-da-{$publishedAtFormatted}-{$locale}"),
            ],
            [
                'teaser' => 'Mehr Automatisierung, mehr Insights, mehr Effizienz',
                'locale' => $locale,
                'author' => 'Sebastian Bürgin-Fix',
                'published_at' => $publishedAt,
                'content' => file_get_contents(database_path("files/news/{$locale}/20250406_docuware_712.md")),
            ]);

        $locale = LocaleEnum::EN->value;

        News::updateOrCreate(
            [
                'title' => 'DocuWare 7.12 is here',
                'slug' => Str::slug("docuware-7-12-ist-here-{$publishedAtFormatted}-{$locale}"),
            ],
            [
                'teaser' => 'More automation, more insights, more efficiency',
                'locale' => $locale,
                'author' => 'Sebastian Bürgin-Fix',
                'published_at' => $publishedAt,
                'content' => file_get_contents(database_path("files/news/{$locale}/20250406_docuware_712.md")),
            ]);
    }
}
