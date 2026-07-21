<?php

namespace Database\Seeders\Codebar;

use App\Models\Contact;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    use ReadsCsv;

    public function run(): void
    {
        foreach ($this->readCsv('contacts.csv') as $row) {
            Contact::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'published' => filter_var($row['published'], FILTER_VALIDATE_BOOLEAN),
                    'sections' => $this->decodeJson($row['sections']),
                    'icons' => $this->decodeJson($row['icons']),
                    'image' => $row['image'],
                ]
            );
        }
    }
}
