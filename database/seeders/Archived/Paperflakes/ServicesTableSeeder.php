<?php

namespace Database\Seeders\Archived\Paperflakes;

use App\Enums\LocaleEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            order: 1,
            sharedSlug: 'dms-ecm-docuware',
            group: 'DMS/ECM',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'DocuWare',
                    'teaser' => 'Intelligentes Dokumentenmanagement mit DocuWare',
                    'tags' => ['DMS/ECM', 'DocuWare'],
                    'content' => file_get_contents(database_path('files/services/de_CH/docuware.md')),
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                ],
                LocaleEnum::EN->value => [
                    'name' => 'DocuWare',
                    'teaser' => 'Smarter Document Management with DocuWare',
                    'tags' => ['DMS/ECM', 'DocuWare'],
                    'content' => file_get_contents(database_path('files/services/en_CH/docuware.md')),
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                ],
            ]
        );

        $this->seed(
            order: 2,
            sharedSlug: 'zunscan-ch',
            group: 'Digitalisierung',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'zunscan.ch',
                    'teaser' => 'Das Scanning Center in der Nordwestschweiz',
                    'tags' => ['Digitalisierung', 'Scanning'],
                    'url' => 'https://zunscan.paperflakes.ch',
                    'content' => null,
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                ],
            ]
        );
    }

    private function seed(int $order, string $sharedSlug, string $group, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($sharedSlug, $order, $group) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return Service::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'order' => $order,
                    'group' => $group,
                    'name' => Arr::get($data, 'name'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                    'url' => Arr::get($data, 'url'),
                    'image' => Arr::get($data, 'image'),
                ]
            );
        });

        $entries->each(function (Service $entry) use ($entries) {
            $entries->each(function (Service $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
