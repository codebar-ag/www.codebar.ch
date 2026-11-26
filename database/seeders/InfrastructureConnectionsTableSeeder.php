<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InfrastructureConnection;
use Illuminate\Database\Seeder;

class InfrastructureConnectionsTableSeeder extends Seeder
{
    private const array CONNECTIONS = [
        ['req', 'domain'],
        ['domain', 'dns'],
        ['dns', 'tls'],
        ['tls', 'waf'],
        ['waf', 'app'],
        ['ohdear', 'waf'],
        ['app', 'cache'],
        ['app', 'db'],
        ['app', 'workers'],
        ['app', 'turnstile'],
        ['app', 'cloudinary'],
        ['app', 'vimeo'],
        ['app', 'spaces'],
        ['workers', 'postmark'],
        ['app', 'azure'],
        ['app', 'macs'],
        ['app', 'logs'],
        ['app', 'analytics'],
        ['app', 'feedback'],
        ['github', 'cicd'],
        ['cicd', 'app'],
        ['github', 'gitlab'],
        ['gitlab', 'synon'],
        ['db', 'synon'],
        ['spaces', 'synon'],
        ['synon', 'synoff'],
    ];

    public function run(): void
    {
        $kept = [];

        foreach (self::CONNECTIONS as $order => [$from, $to]) {
            $kept[] = InfrastructureConnection::query()->updateOrCreate(['from' => $from, 'to' => $to], ['order' => $order])->id;
        }

        InfrastructureConnection::query()->whereNotIn('id', $kept)->delete();
    }
}
