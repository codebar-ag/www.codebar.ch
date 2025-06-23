<?php

namespace Database\Seeders\Paperflakes;

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
                'title' => 'Your Digital Partner',
                'description' => 'We support you with smart digital solutions that move your business forward.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Services that Support You',
                'description' => 'From strategy to implementation - we\'re here to support you all the way.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Tools That Empower You',
                'description' => 'Our products are built to help you work smarter, faster and better.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Legal Notice',
                'description' => 'All legal details about paperflakes AG.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Let\'s Talk',
                'description' => 'Have a question? We\'re here to support you - just reach out.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
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
                'title' => 'Dein digitaler Partner',
                'description' => 'Wir unterstützen dich mit cleveren Lösungen für deinen digitalen Erfolg.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Dienstleistungen für dich',
                'description' => 'Von der Strategie bis zur Umsetzung - wir stehen dir zur Seite.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Tools, die dich stärken',
                'description' => 'Unsere Produkte helfen dir, effizienter, schneller und einfacher zu arbeiten.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Rechtliches',
                'description' => 'Alle rechtlichen Informationen zur paperflakes AG.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        Page::updateOrCreate(
            [
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [

                'robots' => 'index,follow',
                'title' => 'Lass uns sprechen',
                'description' => 'Fragen? Wir sind fürr dich da - melde dich jederzeit bei uns.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);
    }
}
