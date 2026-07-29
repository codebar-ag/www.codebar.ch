<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Team members live as one YAML file per person under database/files/team/.
 * Adding or changing someone means editing that file and running
 * `php artisan team:import`; no code change is needed.
 *
 * This replaced a semicolon-separated CSV that carried JSON inside its cells —
 * unreadable in a diff and effectively uneditable by hand.
 */
class ContactsTableSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('team:import', [], $this->command->getOutput());
    }
}
