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
            ['key' => 'start.index', 'locale' => 'de_CH', 'description' => 'Wir hören zu, denken konzeptionell und entwickeln nutzerzentrierte Software mit offenen Technologien und Standards.'],
            ['key' => 'about-us.index', 'locale' => 'de_CH', 'description' => 'Lerne codebar solutions AG kennen – dein Schweizer Partner für konzeptionelle Softwareentwicklung mit offenen Technologien.'],
            ['key' => 'services.index', 'locale' => 'de_CH', 'description' => 'Wir hören zu, erarbeiten Konzepte für künftige Nutzer:innen und setzen sie um – von der Idee bis zur Software.'],
            ['key' => 'products.index', 'locale' => 'de_CH', 'description' => 'Nutzerzentrierte Softwarelösungen mit echtem Mehrwert – entwickelt mit offenen Technologien und Standards.'],
            ['key' => 'contact.index', 'locale' => 'de_CH', 'description' => 'Hast du eine innovative Idee? Wir hören zu, verstehen deine Bedürfnisse und erwecken deine Vision zum Leben.'],
            ['key' => 'news.index', 'locale' => 'en_CH', 'description' => 'Latest news and expert insights on software development, open technologies and digital innovation from codebar.'],
            ['key' => 'about-us.index', 'locale' => 'en_CH', 'description' => 'Meet codebar solutions AG – your Swiss partner for conceptual software development with open technologies.'],
        ];

        foreach ($pageDescriptions as $page) {
            DB::table('pages')
                ->where('key', $page['key'])
                ->where('locale', $page['locale'])
                ->update(['description' => $page['description']]);
        }
    }
}
