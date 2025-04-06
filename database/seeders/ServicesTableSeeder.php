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
                'name' => 'DMS/ECM',
                'slug' => Str::slug("dms-ecm-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'order' => 1,
                'content' => file_get_contents(database_path("files/services/{$locale}/dms_ecm.md")),

            ]);

        $locale = LocaleEnum::DE->value;

        Service::updateOrCreate(
            [
                'name' => 'DMS/ECM',
                'slug' => Str::slug("dms-ecm-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'order' => 1,
                'content' => file_get_contents(database_path("files/services/{$locale}/dms_ecm.md")),
            ]);

        $locale = LocaleEnum::DE->value;

        Service::updateOrCreate(
            [
                'name' => 'zunscan.ch',
                'slug' => Str::slug("zunscan-ch-{$locale}"),
            ],
            [
                'published' => true,
                'locale' => $locale,
                'order' => 2,
                'content' => null,
                'url' => 'https://zunscan.paperflakes.ch',
            ]);
    }
}
