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
                'order' => 1,
                'content' => null,
            ]);

        Product::updateOrCreate(
            [
                'name' => 'CloudDocs',
                'slug' => 'clouddocs',
            ],
            [
                'order' => 2,
                'content' => null,
            ]);
    }
}
