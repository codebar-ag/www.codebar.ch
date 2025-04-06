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
            ],
            [
                'published' => true,
                'locale' => $locale,
                'order' => 1,
                'teaser' => 'Supercharge Your DMS with Smart Integrations',
                'tags' => ['DocuWare', 'M-Files', 'SharePoint'],
                'content' => file_get_contents(database_path("files/products/{$locale}/docuhub.md")),

            ]);

        $locale = LocaleEnum::EN->value;

        Product::updateOrCreate(
            [
                'name' => 'CloudDocs',
                'slug' => Str::slug("clouddocs-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'order' => 2,
                'teaser' => 'Give Your Customers Secure Access to Their Documents',
                'tags' => ['DocuWare', 'M-Files'],
                'content' => file_get_contents(database_path("files/products/{$locale}/clouddocs.md")),

            ]);
    }
}
