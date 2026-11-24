<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InfrastructureSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(InfrastructureLayersTableSeeder::class);
        $this->call(InfrastructureServicesTableSeeder::class);
        $this->call(InfrastructureConnectionsTableSeeder::class);
        $this->call(InfrastructureStacksTableSeeder::class);
    }
}
