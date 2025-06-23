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
                    ContactSectionEnum::EMPLOYEE_PRODUCTS => [
                        'key' => ContactSectionEnum::EMPLOYEE_PRODUCTS,
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/20201001_new_sebastian_fix_square.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/e2ch9rzadt0hamytyzc8.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/ujywqubl5rkkm5hjqjsa.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/r_lees.webp',
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
                'image' => 'https://www.realestateclub.ch/images/652d4179a3494dedacf6555a_Katja_Jacke.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 6],
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
                'link' => 'https://www.codebar.ch',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/codebar_social_linkedin_profilbild.webp',
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
                        ],
                    ],
                ],
                'link' => 'https://www.business-solutions.gmbh',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-paperflakes-ch/people/wieland_busines_solutions_gmbh.jpg',
            ]
        );
    }
}
