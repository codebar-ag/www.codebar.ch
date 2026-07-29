<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\NetworkUser;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

/**
 * Contact channels are real where known, otherwise placeholder (*@example.com).
 * All rows seed as unpublished — a contact only appears on the network page once
 * explicitly published.
 *
 * Sourced from network_users.csv — the current data export — not hand-authored,
 * so this stays CSV rather than moving to per-item files like networks/services/news.
 */
class NetworkUsersTableSeeder extends Seeder
{
    use ReadsCsv;

    public function run(): void
    {
        collect($this->readCsv('network_users.csv'))->each(function (array $row): void {
            NetworkUser::updateOrCreate(
                [
                    'network_key' => $row['network_key'],
                    'name' => $row['name'],
                ],
                [
                    'role' => $row['role'] !== '' ? $row['role'] : null,
                    'avatar_disk' => $row['avatar_disk'] !== '' ? $row['avatar_disk'] : null,
                    'avatar_path' => $row['avatar_path'] !== '' ? $row['avatar_path'] : null,
                    'avatar_url' => $row['avatar_url'] !== '' ? $row['avatar_url'] : null,
                    'email' => $row['email'] !== '' ? $row['email'] : null,
                    'public_email' => $row['public_email'] !== '' ? $row['public_email'] : null,
                    'linkedin' => $row['linkedin'] !== '' ? $row['linkedin'] : null,
                    'phone' => $row['phone'] !== '' ? $row['phone'] : null,
                    'published' => filter_var($row['published'], FILTER_VALIDATE_BOOLEAN),
                    'sort' => (int) $row['sort'],
                ]
            );
        });
    }
}
