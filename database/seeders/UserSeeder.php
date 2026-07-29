<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use ReadsCsv;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        foreach ($this->readCsv('users.csv') as $row) {
            $user = User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'uuid' => $row['uuid'],
                    'name' => $row['name'],
                    'email_verified_at' => $row['email_verified_at'],
                    'password' => $row['password'],
                    'locale' => $row['locale'],
                ]
            );

            $user->assignRole(RoleEnum::ADMINISTRATOR, RoleEnum::USER);
        }
    }
}
