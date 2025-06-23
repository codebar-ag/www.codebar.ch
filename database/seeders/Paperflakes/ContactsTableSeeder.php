<?php

namespace Database\Seeders\Paperflakes;

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
                    ContactSectionEnum::EMPLOYEE_SERVICES => [
                        'key' => ContactSectionEnum::EMPLOYEE_SERVICES,
                        'role' => [
                            'de_CH' => 'DMS/ECM Spezialist',
                            'en_CH' => 'DMS/ECM Specialist',
                        ],
                    ],
                    /*					ContactSectionEnum::EMPLOYEE_PRODUCTS => [
                        'key' => ContactSectionEnum::EMPLOYEE_PRODUCTS,
                        'role' => [
                            'de_CH' => 'Software-Architekt',
                            'en_CH' => 'Software-Engineer',
                        ],
                    ],*/
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'sebastian.buergin@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/fix-sebastian/',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/s_fix_e_background_removal_f_png.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Mischa Lanz',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEE_SERVICES => [
                        'key' => ContactSectionEnum::EMPLOYEE_SERVICES,
                        'role' => [
                            'de_CH' => 'zunscan.ch',
                        ],
                    ],
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'mischa.lanz@paperflakes.ch',
                    'linkedin' => 'https://www.realestateclub.ch/images/REC_social_linkedin.svg',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/6528f7c6bddf8430fb5d154c_Mischa_Hemd.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Dominique Ernst',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'dominique.ernst@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/dominique-ernst',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/ujywqubl5rkkm5hjqjsa_e_background_removal_f_png.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Rhys Lees',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEE_PRODUCTS => [
                        'key' => ContactSectionEnum::EMPLOYEE_PRODUCTS,
                        'role' => [
                            'de_CH' => 'Entwickler',
                            'en_CH' => 'Developer',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'rhys.leess@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/rhys-lees',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/r_lees_e_background_removal_f_png.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Katja Lanz',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEE_ADMINISTRATION => [
                        'key' => ContactSectionEnum::EMPLOYEE_ADMINISTRATION,
                        'role' => [
                            'de_CH' => 'Finanzen & HR',
                            'en_CH' => 'Finance & HR',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'katja.lanz@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/katja-lanz-a92372149/',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/652d4179a3494dedacf6555a_Katja_Jacke.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'DR-G2110',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEE_ADMINISTRATION => [
                        'key' => ContactSectionEnum::EMPLOYEE_ADMINISTRATION,
                        'role' => [
                            'de_CH' => 'Digitalisierungs-Beauftragter',
                            'en_CH' => 'Head of Digital Transformation',
                        ],
                    ],
                ],
                'icons' => [],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/drg_e_background_removal_f_png.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Sebastian Bürgin-Fix',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'codebar Solutions AG',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'info@codebar.ch',
                    'website' => 'https://www.codebar.ch',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/Zeichenfla%CC%88che_1.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'Dario Wieland',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'Wieland Business Solutions GmbH',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'wieland@business-solutions.gmbh',
                    'website' => 'https://www.business-solutions.gmbh',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/wds.jpg',
            ]
        );
    }
}
