<?php

namespace Database\Seeders;

use App\Models\NetworkUser;
use Illuminate\Database\Seeder;

class NetworkUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeders never run on production — the contact channels below are
     *
     * clearly-fake example data (*@example.com) so the cards, chips and
     * avatars are visible in local and staging environments.
     */
    public function run(): void
    {
        $this->seed(
            networkKey: 'wieland-business-solutions',
            name: 'Dario Wieland',
            sort: 10,
            email: 'dario.wieland@example.com',
            linkedin: 'https://www.linkedin.com/in/example-dario',
            phone: '+41 61 000 00 01',
        );

        $this->seed(
            networkKey: 'pst',
            name: 'Sarah Fässler',
            sort: 10,
            email: 'sarah.faessler@example.com',
            phone: '+41 61 000 00 02',
        );

        $this->seed(
            networkKey: 'docuware',
            name: 'Vincenzo Carbone',
            sort: 10,
            role: 'DocuWare Schweiz',
            email: 'vincenzo.carbone@example.com',
            linkedin: 'https://www.linkedin.com/in/example-vincenzo',
            avatar: '/images/placeholders/avatar-sample.svg',
        );

        $this->seed(
            networkKey: 'odoo',
            name: 'Domenik',
            sort: 10,
            email: 'domenik@example.com',
        );

        $this->seed(
            networkKey: 'iway',
            name: 'Patrick Baumeler',
            sort: 10,
            email: 'patrick.baumeler@example.com',
            linkedin: 'https://www.linkedin.com/in/example-patrick',
            phone: '+41 61 000 00 03',
        );
    }

    private function seed(
        string $networkKey,
        string $name,
        int $sort,
        ?string $role = null,
        ?string $email = null,
        ?string $linkedin = null,
        ?string $phone = null,
        ?string $avatar = null,
    ): void {
        NetworkUser::updateOrCreate(
            [
                'network_key' => $networkKey,
                'name' => $name,
            ],
            [
                'role' => $role,
                'email' => $email,
                'linkedin' => $linkedin,
                'phone' => $phone,
                'avatar' => $avatar,
                'published' => true,
                'sort' => $sort,
            ]
        );
    }
}
