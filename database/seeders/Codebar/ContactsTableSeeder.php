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
                    ContactSectionEnum::SOFTWARE_ENGINERING => [
                        'key' => ContactSectionEnum::SOFTWARE_ENGINERING,
                        'role' => [
                            'de_CH' => 'Software-Architekt',
                            'en_CH' => 'Software-Engineer',
                        ],
                    ],
                    ContactSectionEnum::DIGITAL_TRANSFORMATION => [
                        'key' => ContactSectionEnum::DIGITAL_TRANSFORMATION,
                        'role' => [
                            'de_CH' => 'DMS/ECM-Architekt',
                            'en_CH' => 'DMS/ECM-Engineer',
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
                    ContactSectionEnum::DIGITAL_TRANSFORMATION => [
                        'key' => ContactSectionEnum::DIGITAL_TRANSFORMATION,
                        'role' => [
                            'de_CH' => 'Projektleiterin',
                            'en_CH' => 'Project Manager',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/melanie_buergin.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Tobias Brogle',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::SOFTWARE_ENGINERING => [
                        'key' => ContactSectionEnum::SOFTWARE_ENGINERING,
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
                    ContactSectionEnum::DIGITAL_TRANSFORMATION => [
                        'key' => ContactSectionEnum::DIGITAL_TRANSFORMATION,
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
                    ContactSectionEnum::SOFTWARE_ENGINERING => [
                        'key' => ContactSectionEnum::SOFTWARE_ENGINERING,
                        'role' => [
                            'de_CH' => 'Applikationsentwickler',
                            'en_CH' => 'Application Developer',
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
                'name' => 'Sarah Fässler',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::COLLABORATIONS => [
                        'key' => ContactSectionEnum::COLLABORATIONS,
                        'role' => [
                            'de_CH' => 'PST GmbH',
                            'en_CH' => 'PST GmbH',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'sarah.faessler@pstgmbh.ch',
                    'website' => 'https://www.pstgmbh.ch',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,f_auto,q_auto/www-codebar-ch/contacts/pst_gmbh.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'Julian Leipert',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::SOFTWARE_ENGINERING => [
                        'key' => ContactSectionEnum::SOFTWARE_ENGINERING,
                        'role' => [
                            'de_CH' => 'Applikationsentwickler',
                            'en_CH' => 'Application Developer',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'julian.leipert@codebar.ch',
                    'linkedin' => null,
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/codebar.webp',
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

        Contact::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'DR-G2110',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::SCANNING => [
                        'key' => ContactSectionEnum::SCANNING,
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
            ['id' => 10],
            [
                'name' => 'Mischa Lanz',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::SCANNING => [
                        'key' => ContactSectionEnum::SCANNING,
                        'role' => [
                            'de_CH' => 'zunscan.ch',
                            'en_CH' => 'zunscan.ch',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'mischa.lanz@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/mischa-lanz-672a65112',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/6528f7c6bddf8430fb5d154c_Mischa_Hemd.webp',
            ]
        );

        Contact::updateOrCreate(
            ['id' => 11],
            [
                'name' => 'Dominique Ernst',
                'published' => true,
                'sections' => [
                    ContactSectionEnum::DIGITAL_TRANSFORMATION => [
                        'key' => ContactSectionEnum::DIGITAL_TRANSFORMATION,
                        'role' => [
                            'de_CH' => 'DMS/ECM Berater',
                            'en_CH' => 'DMS/ECM Consultant',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'dominique.ernst@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/dominique-ernst',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/ujywqubl5rkkm5hjqjsa_e_background_removal_f_png.webp',
            ]
        );

    }
}
