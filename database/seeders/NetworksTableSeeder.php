<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use Illuminate\Database\Seeder;

class NetworksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seed(
            key: 'wieland-business-solutions',
            category: NetworkCategoryEnum::COLLABORATION,
            sort: 10,
            localizedData: [
                'de_CH' => ['name' => 'Wieland Business Solutions AG', 'excerpt' => 'Gemeinsame Projekte'],
                'en_CH' => ['name' => 'Wieland Business Solutions AG', 'excerpt' => 'Joint projects'],
            ],
        );

        $this->seed(
            key: 'pst',
            category: NetworkCategoryEnum::COLLABORATION,
            sort: 20,
            localizedData: [
                'de_CH' => ['name' => 'PST GmbH', 'excerpt' => 'Finanzen & Buchhaltung'],
                'en_CH' => ['name' => 'PST GmbH', 'excerpt' => 'Finance & accounting'],
            ],
        );

        $this->seed(
            key: 'docuware',
            category: NetworkCategoryEnum::SOFTWARE,
            sort: 30,
            website: 'https://start.docuware.com',
            localizedData: [
                'de_CH' => ['name' => 'DocuWare', 'excerpt' => 'DMS/ECM'],
                'en_CH' => ['name' => 'DocuWare', 'excerpt' => 'DMS/ECM'],
            ],
        );

        $this->seed(
            key: 'odoo',
            category: NetworkCategoryEnum::SOFTWARE,
            sort: 40,
            website: 'https://www.odoo.com',
            localizedData: [
                'de_CH' => ['name' => 'Odoo', 'excerpt' => 'Open Source ERP'],
                'en_CH' => ['name' => 'Odoo', 'excerpt' => 'Open-source ERP'],
            ],
        );

        $this->seed(
            key: 'iway',
            category: NetworkCategoryEnum::INFRASTRUCTURE,
            sort: 50,
            website: 'https://www.iway.ch',
            localizedData: [
                'de_CH' => ['name' => 'iWay', 'excerpt' => 'Internet & Telefonie'],
                'en_CH' => ['name' => 'iWay', 'excerpt' => 'Internet & telephony'],
            ],
        );

        $this->seed(
            key: 'baselhack',
            category: NetworkCategoryEnum::SPONSORING,
            sort: 60,
            website: 'https://www.baselhack.ch',
            localizedData: [
                'de_CH' => ['name' => 'BaselHack', 'tier_label' => 'Silver Sponsor', 'excerpt' => 'Hackathon Nordwestschweiz'],
                'en_CH' => ['name' => 'BaselHack', 'tier_label' => 'Silver Sponsor', 'excerpt' => 'Hackathon in the Basel region'],
            ],
        );

        $this->seed(
            key: 'swiss-laravel-association',
            category: NetworkCategoryEnum::SPONSORING,
            sort: 70,
            website: 'https://laravel.swiss',
            localizedData: [
                'de_CH' => ['name' => 'Swiss Laravel Association', 'excerpt' => 'Laravel-Community'],
                'en_CH' => ['name' => 'Swiss Laravel Association', 'excerpt' => 'Laravel community'],
            ],
        );

        $this->seed(
            key: 'swiss-made-software',
            category: NetworkCategoryEnum::CERTIFICATION,
            sort: 80,
            website: 'https://www.swissmadesoftware.org/en/about/swiss-made-software.html',
            localizedData: [
                'de_CH' => ['name' => 'Swiss Made Software'],
                'en_CH' => ['name' => 'Swiss Made Software'],
            ],
        );

        $this->seed(
            key: 'swiss-digital-services',
            category: NetworkCategoryEnum::CERTIFICATION,
            sort: 90,
            website: 'https://www.swissmadesoftware.org/en/about/swiss-digital-services.html',
            localizedData: [
                'de_CH' => ['name' => 'Swiss Digital Services'],
                'en_CH' => ['name' => 'Swiss Digital Services'],
            ],
        );
    }

    /**
     * @param  array<string, array<string, string|null>>  $localizedData
     */
    private function seed(
        string $key,
        NetworkCategoryEnum $category,
        int $sort,
        array $localizedData,
        ?string $website = null,
        ?string $pageSlug = null,
        NetworkStatusEnum $status = NetworkStatusEnum::ACTIVE,
        ?int $sinceYear = null,
        ?int $untilYear = null,
    ): void {
        foreach (LocaleEnum::cases() as $locale) {
            $data = $localizedData[$locale->value] ?? null;

            if (! $data) {
                continue;
            }

            Network::updateOrCreate(
                [
                    'key' => $key,
                    'locale' => $locale->value,
                ],
                [
                    'name' => $data['name'],
                    'category' => $category->value,
                    'status' => $status->value,
                    'logo' => $data['logo'] ?? null,
                    'tier_label' => $data['tier_label'] ?? null,
                    'excerpt' => $data['excerpt'] ?? null,
                    'website' => $website,
                    'since_year' => $sinceYear,
                    'until_year' => $untilYear,
                    'page_slug' => $pageSlug,
                    'published' => true,
                    'sort' => $sort,
                ]
            );
        }
    }
}
