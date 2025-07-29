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
                    ContactSectionEnum::EMPLOYEE_SERVICES => [
                        'key' => ContactSectionEnum::EMPLOYEE_SERVICES,
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
                'name' => 'Rhys Lees',
                'published' => false,
                'sections' => [
                    ContactSectionEnum::EMPLOYEE_SERVICES => [
                        'key' => ContactSectionEnum::EMPLOYEE_SERVICES,
                        'role' => [
                            'de_CH' => 'Entwickler',
                            'en_CH' => 'Developer',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'rhys.leess@codebar.ch',
                    'linkedin' => 'https://www.linkedin.com/in/rhys-lees',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/r_lees_e_background_removal_f_png.webp',
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
                            'de_CH' => 'paperflakes AG',
                            'en_CH' => 'paperflakes AG',
                        ],
                    ],
                ],
                'icons' => [
                    'email' => 'info@paperflakes.ch',
                    'website' => 'https://www.paperflakes.ch',
                ],
                'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/paperflakes.webp',
            ]
        );
    }
}
