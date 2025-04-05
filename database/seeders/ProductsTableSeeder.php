<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::updateOrCreate(
            [
                'name' => 'DocuHub',
                'slug' => 'docuhub',
            ],
            [
                'published' => true,
                'order' => 1,
                'teaser' => 'Supercharge Your DMS with Smart Integrations',
                'content' => file_get_contents(database_path('files/products_docuhub.md')),
            ]);

        Product::updateOrCreate(
            [
                'name' => 'CloudDocs',
                'slug' => 'clouddocs',
            ],
            [
                'published' => true,
                'order' => 2,
                'teaser' => 'Give Your Customers Secure Access to Their Documents',
                'content' => file_get_contents(database_path('files/products_clouddocs.md')),
            ]);
    }
}
