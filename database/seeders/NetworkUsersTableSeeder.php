<?php

namespace Database\Seeders;

use App\Models\NetworkUser;
use Illuminate\Database\Seeder;

class NetworkUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Contact channels are real where known, otherwise placeholder
     *
     * (*@example.com). All rows seed as unpublished — a contact only
     * appears on the network page once explicitly published.
     */
    public function run(): void
    {
        $this->seed(
            networkKey: 'wieland-business-solutions',
            name: 'Dario Wieland',
            sort: 10,
            email: 'dario.wieland@business-solutions.gmbh',
        );

        $this->seed(
            networkKey: 'pst',
            name: 'Sarah Fässler',
            sort: 10,
            email: 'sarah.faessler@pstgmbh.ch',
        );

        $this->seed(
            networkKey: 'docuware',
            name: 'Vincenzo Carbone',
            sort: 10,
            role: 'DocuWare Schweiz',
            email: 'vincenzo.carbone@docuware.com',
            avatarUrl: '/images/placeholders/avatar-sample.svg',
        );

        $this->seed(
            networkKey: 'odoo',
            name: 'Domenik Friedrich',
            sort: 10,
            email: 'domf@odoo.com',
        );

        $this->seed(
            networkKey: 'baselhack',
            name: 'BaselHack',
            sort: 10,
            email: 'info@baselhack.ch',
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
        ?string $avatarUrl = null,
    ): void {
        NetworkUser::updateOrCreate(
            [
                'network_key' => $networkKey,
                'name' => $name,
            ],
            [
                'role' => $role,
                'email' => $email,
                'public_email' => $email,
                'linkedin' => $linkedin,
                'phone' => $phone,
                'avatar_url' => $avatarUrl,
                'published' => false,
                'sort' => $sort,
            ]
        );
    }
}
