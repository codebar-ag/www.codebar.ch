<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale = LocaleEnum::EN->value;

        Product::updateOrCreate(
            [
                'name' => 'DocuHub',
                'slug' => Str::slug("docuhub-{$locale}"),
                'locale' => $locale,
            ],
            [
                'published' => true,
                'order' => 1,
                'teaser' => 'Supercharge Your DMS with Smart Integrations',
                'tags' => ['DocuWare', 'M-Files', 'SharePoint'],
                'content' => file_get_contents(database_path("files/products/{$locale}/docuhub.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        $locale = LocaleEnum::DE->value;

        Product::updateOrCreate(
            [
                'name' => 'DocuHub',
                'slug' => Str::slug("docuhub-{$locale}"),
                'locale' => $locale,
            ],
            [
                'published' => true,
                'order' => 1,
                'teaser' => 'Bring dein DMS mit smarten Integrationen auf das nächste Level',
                'tags' => ['DocuWare', 'M-Files', 'SharePoint'],
                'content' => file_get_contents(database_path("files/products/{$locale}/docuhub.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        $locale = LocaleEnum::EN->value;

        Product::updateOrCreate(
            [
                'name' => 'CloudDocs',
                'slug' => Str::slug("clouddocs-{$locale}"),
                'locale' => $locale,
            ],
            [
                'published' => true,
                'order' => 2,
                'teaser' => 'Give Your Customers Secure Access to Their Documents',
                'tags' => ['DocuWare', 'M-Files'],
                'content' => file_get_contents(database_path("files/products/{$locale}/clouddocs.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        $locale = LocaleEnum::DE->value;

        Product::updateOrCreate(
            [
                'name' => 'CloudDocs',
                'slug' => Str::slug("clouddocs-{$locale}"),
                'locale' => $locale,
            ],
            [
                'published' => true,
                'order' => 2,
                'teaser' => 'Gib deinen Kunden sicheren Zugriff auf ihre Dokumente',
                'tags' => ['DocuWare', 'M-Files'],
                'content' => file_get_contents(database_path("files/products/{$locale}/clouddocs.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);
    }
}
