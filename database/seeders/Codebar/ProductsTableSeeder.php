<?php

namespace Database\Seeders\Codebar;

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
            sharedSlug: 'flows',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'Flows',
                    'teaser' => 'Platzhalter — mit echtem Produkttext ersetzen',
                    'tags' => [],
                    'content' => null,
                ],
                LocaleEnum::EN->value => [
                    'name' => 'Flows',
                    'teaser' => 'Placeholder — replace with real product copy',
                    'tags' => [],
                    'content' => null,
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
                    'image' => Arr::get($data, 'image', 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp'),
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
