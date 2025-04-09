<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale = LocaleEnum::EN->value;

        Service::updateOrCreate(
            [
                'name' => 'DocuWare',
                'slug' => Str::slug("dms-ecm-docuware-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'group' => 'DMS/ECM',
                'teaser' => 'Smarter Document Management with DocuWare',
                'order' => 1,
                'tags' => [
                    'DMS/ECM',
                ],
                'content' => file_get_contents(database_path("files/services/{$locale}/docuware.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        $locale = LocaleEnum::DE->value;

        Service::updateOrCreate(
            [
                'name' => 'DocuWare',
                'slug' => Str::slug("dms-ecm-docuware-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'group' => 'DMS/ECM',
                'teaser' => 'Intelligentes Dokumentenmanagement mit DocuWare',
                'order' => 1,
                'tags' => [
                    'DMS/ECM',
                ],
                'content' => file_get_contents(database_path("files/services/{$locale}/docuware.md")),
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);

        $locale = LocaleEnum::DE->value;

        Service::updateOrCreate(
            [
                'name' => 'zunscan.ch',
                'slug' => Str::slug("zunscan-ch-{$locale}"),
            ],
            [
                'published' => true,
                'group' => 'Digitalisierung',
                'locale' => $locale,
                'teaser' => 'Das Scanning Center in der Nordwestschweiz',
                'order' => 2,
                'content' => null,
                'tags' => [
                    'Digitalisierung',
                    'Scanning',
                ],
                'url' => 'https://zunscan.paperflakes.ch',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
            ]);
    }
}
