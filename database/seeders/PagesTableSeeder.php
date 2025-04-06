<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->deCH();
        $this->enCH();
    }

    private function enCH()
    {
        $locale = LocaleEnum::EN->value;

        Page::updateOrCreate(
            [
                'key' => 'start.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Start Index',
                'description' => 'Start Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Services Index',
                'description' => 'Services Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Products Index',
                'description' => 'Products Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Legal Imprint Index',
                'description' => 'Legal Imprint Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Contact Index',
                'description' => 'Contact Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);
    }

    private function deCH()
    {
        $locale = LocaleEnum::DE->value;

        Page::updateOrCreate(
            [
                'key' => 'start.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Start Index',
                'description' => 'Start Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Dienstleistungen Index',
                'description' => 'Dienstleistungen Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Produkte Index',
                'description' => 'Produkte Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Impressum Index',
                'description' => 'Impressum Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [

                'robots' => 'index,follow',
                'title' => 'Kontakt Index',
                'description' => 'Kontakt Description',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.avif',
            ]);
    }
}
