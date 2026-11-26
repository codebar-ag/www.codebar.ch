<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InfrastructureStack;
use Illuminate\Database\Seeder;

class InfrastructureStacksTableSeeder extends Seeder
{
    public function run(): void
    {
        InfrastructureStack::query()->where('slug', '!=', 'web.codebar')->where('is_default', true)->update(['is_default' => false]);

        InfrastructureStack::query()->updateOrCreate(['slug' => 'web.codebar'], [
            'is_default' => true,
            'name' => 'codebar Solutions AG',
            'domain' => 'www.codebar.ch',
            'image' => 'images/infrastructure/front-codebar.svg',
            'sketch' => null,
            'services' => ['domain', 'dns', 'tls', 'waf', 'app', 'cache', 'db', 'workers', 'turnstile', 'cloudinary', 'vimeo', 'postmark', 'spaces', 'azure', 'macs', 'ohdear', 'logs', 'analytics', 'feedback', 'github', 'cicd', 'gitlab', 'synon', 'synoff'],
            'overrides' => [
                'cache' => [
                    'provider' => 'Neon',
                ],
            ],
        ]);
    }
}
