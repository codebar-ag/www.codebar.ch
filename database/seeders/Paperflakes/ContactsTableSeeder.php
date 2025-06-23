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
            ['name' => 'Sebastian Bürgin-Fix'],
            [
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
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'codebar Solutions AG',
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
                'link' => 'https://www.codebar.ch',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-codebar-ch/team/s_fix.webp',
            ]
        );

        Contact::updateOrCreate(
            ['name' => 'Mischa Lanz'],
            [
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
                'image' => 'https://www.realestateclub.ch/images/6528f7c6bddf8430fb5d154c_Mischa_Hemd.webp',
            ]
        );

        Contact::updateOrCreate(
            ['name' => 'Dominique Ernst'],
            [
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-codebar-ch/team/d_ernst.webp',
            ]
        );

        Contact::updateOrCreate(
            ['name' => 'Rhys Lees'],
            [
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_500/www-codebar-ch/team/r_lees.webp',
            ]
        );

        Contact::updateOrCreate(
            ['name' => 'Katja Lanz'],
            [
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
            ['name' => 'Dario Wieland'],
            [
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
                'image' => 'https://cdn.prod.website-files.com/6727978a2c75d658e43b75d5/67543ad5eb733f845bc34ba6_DarioWieland_Web.webp',
            ]
        );
    }
}
