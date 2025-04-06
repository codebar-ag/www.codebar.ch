<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale = LocaleEnum::DE->value;

        Product::updateOrCreate(
            [
                'key' => 'start.index',
            ],
            [
                'locale' => $locale,
                'robots' => 'index,follow',
                'title' => '',
                'description' => '',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

    }
}
