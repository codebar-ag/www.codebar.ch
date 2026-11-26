<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InfrastructureLayer;
use Illuminate\Database\Seeder;

class InfrastructureLayersTableSeeder extends Seeder
{
    private const array LAYERS = [
        [
            'key' => 'network',
            'order' => 1,
            'color' => '#6F9CC3',
            'name' => [
                'de_CH' => 'Netzwerk',
                'en_CH' => 'Network',
            ],
        ],
        [
            'key' => 'platform',
            'order' => 2,
            'color' => '#5FA8A3',
            'name' => [
                'de_CH' => 'Plattform',
                'en_CH' => 'Platform',
            ],
        ],
        [
            'key' => 'services',
            'order' => 3,
            'color' => '#D8B36A',
            'name' => [
                'de_CH' => 'Services',
                'en_CH' => 'Services',
            ],
        ],
        [
            'key' => 'ai',
            'order' => 4,
            'color' => '#A487C2',
            'name' => [
                'de_CH' => 'KI',
                'en_CH' => 'AI',
            ],
        ],
        [
            'key' => 'monitoring',
            'order' => 5,
            'color' => '#D38B72',
            'name' => [
                'de_CH' => 'Monitoring',
                'en_CH' => 'Monitoring',
            ],
        ],
        [
            'key' => 'development',
            'order' => 6,
            'color' => '#86B38C',
            'name' => [
                'de_CH' => 'Entwicklung',
                'en_CH' => 'Development',
            ],
        ],
        [
            'key' => 'backups',
            'order' => 7,
            'color' => '#7A8696',
            'name' => [
                'de_CH' => 'Backups',
                'en_CH' => 'Backups',
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::LAYERS as $layer) {
            InfrastructureLayer::query()->updateOrCreate(['key' => $layer['key']], $layer);
        }

        InfrastructureLayer::query()->whereNotIn('key', array_column(self::LAYERS, 'key'))->delete();
    }
}
