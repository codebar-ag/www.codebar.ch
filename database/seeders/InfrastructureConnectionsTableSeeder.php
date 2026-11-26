<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\InfrastructureFlowEnum;
use App\Models\InfrastructureConnection;
use Illuminate\Database\Seeder;

class InfrastructureConnectionsTableSeeder extends Seeder
{
    private const array CONNECTIONS = [
        ['req', 'domain', InfrastructureFlowEnum::CONSTANT],
        ['domain', 'dns', InfrastructureFlowEnum::FREQUENT],
        ['dns', 'tls', InfrastructureFlowEnum::FREQUENT],
        ['tls', 'waf', InfrastructureFlowEnum::CONSTANT],
        ['waf', 'app', InfrastructureFlowEnum::CONSTANT],
        ['ohdear', 'waf', InfrastructureFlowEnum::FREQUENT],
        ['app', 'cache', InfrastructureFlowEnum::CONSTANT],
        ['app', 'db', InfrastructureFlowEnum::CONSTANT],
        ['app', 'workers', InfrastructureFlowEnum::CONSTANT],
        ['app', 'turnstile', InfrastructureFlowEnum::OCCASIONAL],
        ['app', 'cloudinary', InfrastructureFlowEnum::CONSTANT],
        ['app', 'vimeo', InfrastructureFlowEnum::FREQUENT],
        ['app', 'spaces', InfrastructureFlowEnum::FREQUENT],
        ['workers', 'postmark', InfrastructureFlowEnum::OCCASIONAL],
        ['app', 'azure', InfrastructureFlowEnum::OCCASIONAL],
        ['app', 'macs', InfrastructureFlowEnum::OCCASIONAL],
        ['app', 'logs', InfrastructureFlowEnum::CONSTANT],
        ['app', 'analytics', InfrastructureFlowEnum::CONSTANT],
        ['app', 'feedback', InfrastructureFlowEnum::OCCASIONAL],
        ['github', 'cicd', InfrastructureFlowEnum::OCCASIONAL],
        ['cicd', 'app', InfrastructureFlowEnum::OCCASIONAL],
        ['github', 'gitlab', InfrastructureFlowEnum::SCHEDULED],
        ['gitlab', 'synon', InfrastructureFlowEnum::SCHEDULED],
        ['db', 'synon', InfrastructureFlowEnum::SCHEDULED],
        ['spaces', 'synon', InfrastructureFlowEnum::SCHEDULED],
        ['synon', 'synoff', InfrastructureFlowEnum::SCHEDULED],
    ];

    public function run(): void
    {
        $kept = [];

        foreach (self::CONNECTIONS as $order => [$from, $to, $flow]) {
            $kept[] = InfrastructureConnection::query()->updateOrCreate(['from' => $from, 'to' => $to], ['flow' => $flow, 'order' => $order])->id;
        }

        InfrastructureConnection::query()->whereNotIn('id', $kept)->delete();
    }
}
