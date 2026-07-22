<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoImageCleanupSeeder extends Seeder
{
    private const BROKEN_IMAGE_PATTERN = '%seo_codebar.webp%';

    private const LEGACY_IMAGE_PATTERN = '%seo_paperflakes.webp%';

    /**
     * Clears out references to two SEO images that were replaced — a broken
     * upload and a legacy placeholder — and reapplies the page descriptions
     * that shipped alongside that image swap.
     */
    public function run(): void
    {
        DB::table('pages')
            ->where(function ($query) {
                $query->where('image', 'like', self::BROKEN_IMAGE_PATTERN)
                    ->orWhere('image', 'like', self::LEGACY_IMAGE_PATTERN);
            })
            ->update(['image' => null]);

        foreach (['news', 'technologies', 'open_sources'] as $table) {
            DB::table($table)
                ->where(function ($query) {
                    $query->where('image', 'like', self::BROKEN_IMAGE_PATTERN)
                        ->orWhere('image', 'like', self::LEGACY_IMAGE_PATTERN);
                })
                ->update(['image' => '']);
        }

        $pageDescriptions = [
            ['key' => 'start.index', 'locale' => 'de_CH', 'description' => 'Wir hören zu, verstehen dein Problem und begleiten dich von der ersten Idee bis zur Software im täglichen Einsatz. Ein kleines Team aus der Region Basel.'],
            ['key' => 'about-us.index', 'locale' => 'de_CH', 'description' => 'Klein aus Überzeugung: kurze Wege, direkte Ansprechpersonen – und Ausbildung als Teil unserer Kultur. Lerne die Menschen hinter codebar kennen.'],
            ['key' => 'services.index', 'locale' => 'de_CH', 'description' => 'Vier Bereiche, ein Weg: von der ersten Idee bis zum laufenden Betrieb – Konzeption, Softwareentwicklung, Dokumentenmanagement und ERP.'],
            ['key' => 'products.index', 'locale' => 'de_CH', 'description' => 'Nutzerzentrierte Softwarelösungen mit echtem Mehrwert – entwickelt mit offenen Technologien und Standards.'],
            ['key' => 'contact.index', 'locale' => 'de_CH', 'description' => 'Erzähl uns von deiner Idee oder deinem Projekt – wir hören zu. Telefon, E-Mail und unsere zwei Standorte in der Region Basel.'],
            ['key' => 'news.index', 'locale' => 'en_CH', 'description' => 'Latest news and expert insights on software development, open technologies and digital innovation from codebar.'],
            ['key' => 'about-us.index', 'locale' => 'en_CH', 'description' => 'Small by conviction: short paths, direct contacts – and training as part of our culture. Meet the people behind codebar.'],
        ];

        foreach ($pageDescriptions as $page) {
            DB::table('pages')
                ->where('key', $page['key'])
                ->where('locale', $page['locale'])
                ->update(['description' => $page['description']]);
        }
    }
}
