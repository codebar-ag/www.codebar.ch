<?php

namespace Database\Seeders\Paperflakes;

use App\Enums\LocaleEnum;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            order: 1,
            sharedSlug: 'docuhub',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'DocuHub',
                    'teaser' => 'Bring dein DMS mit smarten Integrationen auf das nächste Level',
                    'tags' => ['DocuWare', 'M-Files', 'SharePoint'],
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/products/de_CH/docuhub.md')),
                ],
                LocaleEnum::EN->value => [
                    'name' => 'DocuHub',
                    'teaser' => 'Supercharge Your DMS with Smart Integrations',
                    'tags' => ['DocuWare', 'M-Files', 'SharePoint'],
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/products/en_CH/docuhub.md')),
                ],
            ]
        );

        $this->seed(
            order: 2,
            sharedSlug: 'clouddocs',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'CloudDocs',
                    'teaser' => 'Gib deinen Kunden sicheren Zugriff auf ihre Dokumente',
                    'tags' => ['DocuWare', 'M-Files'],
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/products/de_CH/clouddocs.md')),
                ],
                LocaleEnum::EN->value => [
                    'name' => 'CloudDocs',
                    'teaser' => 'Give Your Customers Secure Access to Their Documents',
                    'tags' => ['DocuWare', 'M-Files'],
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => file_get_contents(database_path('files/products/en_CH/clouddocs.md')),
                ],
            ]
        );
    }

    private function seed(int $order, string $sharedSlug, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($sharedSlug, $order) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return Product::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'order' => $order,
                    'name' => Arr::get($data, 'name'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'tags' => Arr::get($data, 'tags', []),
                    'image' => Arr::get($data, 'image'),
                    'content' => Arr::get($data, 'content'),
                ]
            );
        });

        $entries->each(function (Product $entry) use ($entries) {
            $entries->each(function (Product $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
