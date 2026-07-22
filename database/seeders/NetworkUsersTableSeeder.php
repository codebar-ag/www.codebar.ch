<?php

namespace Database\Seeders;

use App\Models\NetworkUser;
use Illuminate\Database\Seeder;

class NetworkUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Contact persons start unpublished: they go live once the person has
     * confirmed via the self-service link (or codebar has their consent).
     */
    public function run(): void
    {
        $this->seed(networkKey: 'wieland-business-solutions', name: 'Dario Wieland', sort: 10);
        $this->seed(networkKey: 'pst', name: 'Sarah Fässler', sort: 10);
        $this->seed(networkKey: 'docuware', name: 'Vincenzo Carbone', role: 'DocuWare Schweiz', sort: 10);
        $this->seed(networkKey: 'odoo', name: 'Domenik', sort: 10);
        $this->seed(networkKey: 'iway', name: 'Patrick Baumeler', sort: 10);
    }

    private function seed(
        string $networkKey,
        string $name,
        int $sort,
        ?string $role = null,
        ?string $email = null,
    ): void {
        NetworkUser::updateOrCreate(
            [
                'network_key' => $networkKey,
                'name' => $name,
            ],
            [
                'role' => $role,
                'email' => $email,
                'published' => false,
                'sort' => $sort,
            ]
        );
    }
}
