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
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'zunscan.ch',
                            'en_CH' => 'zunscan.ch',
                        ],
                    ],
                    ContactSectionEnum::BOARD_MEMBERS => [
                        'key' => ContactSectionEnum::BOARD_MEMBERS,
                    ],
                ],
                'icons' => [
                    'email' => 'mischa.lanz@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/mischa-lanz-672a65112',
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
                    /*                     ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'DMS/ECM Berater',
                            'en_CH' => 'DMS/ECM Consultant',
                        ],
                    ], */
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
                'name' => 'Katja Lanz',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
                        'role' => [
                            'de_CH' => 'HR',
                            'en_CH' => 'HR',
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
            ['id' => 5],
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
                ],
                'icons' => [
                    'email' => 'melanie.buergin@paperflakes.ch',
                    'linkedin' => 'https://www.linkedin.com/in/melanie-buergin',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/paperflakes.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'DR-G2110',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::EMPLOYEES => [
                        'key' => ContactSectionEnum::EMPLOYEES,
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/paperflakes.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Dario Wieland',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'Wieland Business Solutions GmbH',
                            'en_CH' => 'Wieland Business Solutions GmbH',
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
