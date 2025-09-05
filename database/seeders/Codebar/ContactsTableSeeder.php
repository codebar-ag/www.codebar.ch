<?php

namespace Database\Seeders\Codebar;

use App\Enums\ContactSectionEnum;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    public function run(): void
    {
        Contact::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Sebastian Bürgin-Fix',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'Software-Architekt',
                            'en_CH' => 'Software-Engineer',
                        ],
                    ],
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'sebastian.buergin@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/fix-sebastian/',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/s_fix_e_background_removal_f_png.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Melanie Bürgin-Fix',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'Administration',
                            'en_CH' => 'Administration',
                        ],
                    ],
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'melanie.buergin@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/melanie-buergin',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/codebar.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Tobias Brogle',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'Applikationsentwickler',
                            'en_CH' => 'Application Developer',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'tobias.brogle@codebar.ch',
                    'linkedin' => null,
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/codebar.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Alexander Christoph Boll',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'Produkt- und Innovationsmanagement',
                            'en_CH' => 'Product and Innovation Management',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'alexander.boll@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/alexanderboll',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/alexander_boll.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Faissal Wahabali',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'Produkt- und Innovationsmanagement',
                            'en_CH' => 'Product and Innovation Management',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'faissal.wahabali@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/faissaloux/',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/faissal_wahabali.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'PST GmbH',
                'published' => false,
                'sections' => [
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'Finanzen',
                            'en_CH' => 'Finance',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'info@pstgmbh.ch',
                    'website' => 'https://www.pstgmbh.ch',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/codebar.webp',
            ]
        );

    }
}
