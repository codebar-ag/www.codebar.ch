<?php

namespace Database\Seeders\Codebar;

use App\Models\OpenSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OpenSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seed(
            sharedSlug: 'laravel-zendesk',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Zendesk',
                    'teaser' => 'Nahtlose Integration von Zendesk-Supportfunktionen in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Zendesk'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Zendesk',
                    'teaser' => 'Seamless integration of Zendesk support features into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Zendesk'],
                ],
            ],
            link: 'https://github.com/codebar-ag/laravel-zendesk',
            downloads: 684,
            version: 'v12.0.1',
        );

        $this->seed(
            identifier: 'codebar-ag/laravel-prerender',
            sharedSlug: 'Laravel Prerender',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Prerender',
                    'teaser' => '',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Prerender'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Prerender',
                    'teaser' => '',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel', 'Prerender'],
                ],
            ],
            link: 'https://github.com/codebar-ag/prerender-middleware-for-prerendering-javascript-rendered-pages-on-the-fly-for-seo',
            downloads: 154600,
            version: 'v12.1.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v1220-downloads-212k-laravel,-docuware,-codebar-ag,-docuware,-codebar-solutions-ag,-ricoh-schweiz-ag,-docu-ware',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => '',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel flysystem cloudinary\ncloudinary flysystem v1 integration with laravel'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => '',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel flysystem cloudinary\ncloudinary flysystem v1 integration with laravel'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v1220-downloads-212k-laravel,-docuware,-codebar-ag,-docuware,-codebar-solutions-ag,-ricoh-schweiz-ag,-docu-ware',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'default-boilerplate-integration-for-projects-at-codebar-solutions-ag',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Default',
                    'teaser' => 'Integration von Laravel Default in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv12.2.0\ndownloads\n14.4k\nlaravel', 'Laravel-default', 'Codebar-ag'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Default',
                    'teaser' => 'Integrate Laravel Default into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv12.2.0\ndownloads\n14.4k\nlaravel', 'Laravel-default', 'Codebar-ag'],
                ],
            ],
            link: 'https://github.com/codebar-ag/default-boilerplate-integration-for-projects-at-codebar-solutions-ag',
            downloads: 13,
            version: 'v4.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v20-downloads-135k-security,-headers,-laravel,-feature-policy,-feature-policy,-codebar-ag',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel default nova\nboilerplate integration for laravel nova projects at codebar solutions ag.'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel default nova\nboilerplate integration for laravel nova projects at codebar solutions ag.'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v20-downloads-135k-security,-headers,-laravel,-feature-policy,-feature-policy,-codebar-ag',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'flysystem-cloudinary-nova-this-is-my-package-flysystem-cloudinary-nova',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Flysystem Cloudinary Nova',
                    'teaser' => 'Integration von Laravel Flysystem Cloudinary Nova in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv1.0\ndownloads\n10.4k\nlaravel', 'Laravel-flysystem-cloudinary-nova', 'Codebar-ag'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Flysystem Cloudinary Nova',
                    'teaser' => 'Integrate Laravel Flysystem Cloudinary Nova into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv1.0\ndownloads\n10.4k\nlaravel', 'Laravel-flysystem-cloudinary-nova', 'Codebar-ag'],
                ],
            ],
            link: 'https://github.com/codebar-ag/flysystem-cloudinary-nova-this-is-my-package-flysystem-cloudinary-nova',
            downloads: 9,
            version: 'v12.2.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v1301-downloads-55k-laravel,-codebarag,-filament-json-field',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel filament json field\na laravel filament json field integration with codemirror support'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel filament json field\na laravel filament json field integration with codemirror support'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v1301-downloads-55k-laravel,-codebarag,-filament-json-field',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'zammad-zammad-integration-with-laravel',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Zammad',
                    'teaser' => 'Integration von Laravel Zammad in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Zammad', 'Packagist\nv13.0.1\ndownloads\n5.1k\nlaravel', 'Codebar-ag', 'Laravel-zammad'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Zammad',
                    'teaser' => 'Integrate Laravel Zammad into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Zammad', 'Packagist\nv13.0.1\ndownloads\n5.1k\nlaravel', 'Codebar-ag', 'Laravel-zammad'],
                ],
            ],
            link: 'https://github.com/codebar-ag/zammad-zammad-integration-with-laravel',
            downloads: 4,
            version: 'v12.2.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v30-downloads-33k-laravel,-postfinance,-b2b,-codebar-ag,-postfinance-b2b',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel auth\nthis is my package laravel-auth'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel auth\nthis is my package laravel-auth'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v30-downloads-33k-laravel,-postfinance,-b2b,-codebar-ag,-postfinance-b2b',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'bexio-bexio-integration-with-laravel',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Bexio',
                    'teaser' => 'Integration von Laravel Bexio in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv11.0\ndownloads\n2.8k\nlaravel', 'Bexio', 'Laravel-bexio', 'Codebar-ag'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Bexio',
                    'teaser' => 'Integrate Laravel Bexio into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv11.0\ndownloads\n2.8k\nlaravel', 'Bexio', 'Laravel-bexio', 'Codebar-ag'],
                ],
            ],
            link: 'https://github.com/codebar-ag/bexio-bexio-integration-with-laravel',
            downloads: 2,
            version: 'v13.0.1',
        );
        $this->seed(
            sharedSlug: 'packagist-v1210-downloads-17k-package,-php,-laravel,-filament',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel twilio verify\ntwilio verify integration with laravel'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel twilio verify\ntwilio verify integration with laravel'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v1210-downloads-17k-package,-php,-laravel,-filament',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'microsoft-planner-this-is-my-package-microsoft-planner',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Microsoft Planner',
                    'teaser' => 'Integration von Laravel Microsoft Planner in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel-microsoft-planner', 'Packagist\nv13.0.1\ndownloads\n1k\nlaravel', 'Codebar-ag'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Microsoft Planner',
                    'teaser' => 'Integrate Laravel Microsoft Planner into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel-microsoft-planner', 'Packagist\nv13.0.1\ndownloads\n1k\nlaravel', 'Codebar-ag'],
                ],
            ],
            link: 'https://github.com/codebar-ag/microsoft-planner-this-is-my-package-microsoft-planner',
            downloads: 1000,
            version: 'v12.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v1201-downloads-865-laravel,-codebar-ag,-flatfox,-flatfox',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel zendesk\nzendesk integration with laravel'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel zendesk\nzendesk integration with laravel'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v1201-downloads-865-laravel,-codebar-ag,-flatfox,-flatfox',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'instagram-this-is-my-package-instagram',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Instagram',
                    'teaser' => 'Integration von Laravel Instagram in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv12.0.1\ndownloads\n336\nlaravel', 'Codebar solutions ag', 'Laravel-instagram'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Instagram',
                    'teaser' => 'Integrate Laravel Instagram into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv12.0.1\ndownloads\n336\nlaravel', 'Codebar solutions ag', 'Laravel-instagram'],
                ],
            ],
            link: 'https://github.com/codebar-ag/instagram-this-is-my-package-instagram',
            downloads: 192,
            version: 'v12.1.0',
        );
        $this->seed(
            sharedSlug: 'packagist-v1301-downloads-109-laravel,-pwa,-codebar-solutions-ag',
            localizedData: [
                'de_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integration von packagist in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Filament revealable field\nthis is my package laravel-filament-revealable-field'],
                ],
                'en_CH' => [
                    'title' => 'packagist',
                    'teaser' => 'Integrate packagist into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Filament revealable field\nthis is my package laravel-filament-revealable-field'],
                ],
            ],
            link: 'https://github.com/codebar-ag/packagist-v1301-downloads-109-laravel,-pwa,-codebar-solutions-ag',
            downloads: 0,
            version: '',
        );
        $this->seed(
            sharedSlug: 'beekeeper-this-is-my-package-beekeeper',
            localizedData: [
                'de_CH' => [
                    'title' => 'Laravel Beekeeper',
                    'teaser' => 'Integration von Laravel Beekeeper in deine Laravel-Anwendung.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Packagist\nv12.1.0\ndownloads\n1\nlaravel', 'Laravel-beekeeper', 'Codebar solutions ag'],
                ],
                'en_CH' => [
                    'title' => 'Laravel Beekeeper',
                    'teaser' => 'Integrate Laravel Beekeeper into your Laravel application.',
                    'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp',
                    'content' => null,
                    'tags' => ['Laravel-beekeeper', 'Codebar solutions ag'],
                ],
            ],
        );
    }

    private function seed(
        string $sharedSlug,
        array $localizedData,
        ?string $link = null,
        ?int $downloads = null,
        ?string $version = null,
        ?string $identifier = null
    ): void {
        $slug = Str::slug($sharedSlug);

        $entries = collect($localizedData)->map(function ($data, $locale) use ($slug, $link, $downloads, $version) {

            return OpenSource::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'title' => Arr::get($data, 'title'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'image' => Arr::get($data, 'image'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                    'link' => $link,
                    'downloads' => $downloads,
                    'version' => $version,
                ]
            );
        });

        $entries->each(function (OpenSource $entry) use ($entries) {
            $entries->each(function (OpenSource $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
