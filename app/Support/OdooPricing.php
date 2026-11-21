<?php

declare(strict_types=1);

namespace App\Support;

class OdooPricing
{
    /**
     * @return array<string, int>
     */
    public static function calculate(int $fullUsers, int $lightUsers, string $billing = 'yearly'): array
    {
        $billing = $billing === 'monthly' ? 'monthly' : 'yearly';
        $max = config()->integer('odoo-pricing.max_users');
        $fullUsers = max(config()->integer('odoo-pricing.full_users.min'), min($max, $fullUsers));
        $lightUsers = max(config()->integer('odoo-pricing.light_users.min'), min($max - $fullUsers, $lightUsers));

        if ($fullUsers + $lightUsers < config()->integer('odoo-pricing.min_users')) {
            $fullUsers = config()->integer('odoo-pricing.min_users');
        }

        $users = $fullUsers + $lightUsers;
        $staging = config()->integer('odoo-pricing.staging');

        $tier = self::tier($users);
        $rate = config()->float('odoo-pricing.eur_to_chf');
        $factor = config()->float('odoo-pricing.hosting.factor.'.$billing);

        $price = fn (string $key, float $by = 1.0): int => (int) round(config()->integer('odoo-pricing.'.$key) * $by * $rate);

        $fullUnit = $price('license.full.'.$billing);
        $lightUnit = $price('license.light.'.$billing);
        $workerUnit = $price('hosting.worker', $factor);
        $storageUnit = $price('hosting.storage_gb', $factor);
        $stagingUnit = $price('hosting.staging', $factor);

        $full = $fullUsers * $fullUnit;
        $light = $lightUsers * $lightUnit;
        $worker = $tier['workers'] * $workerUnit;
        $storageWanted = $users * config()->integer('odoo-pricing.storage_gb_per_user');
        $storageGb = min($storageWanted, config()->integer('odoo-pricing.storage_gb_max'));
        $storage = $storageGb * $storageUnit;
        $stagingTotal = $staging * $stagingUnit;
        $hosting = $worker + $storage + $stagingTotal;

        return [
            'full_users' => $fullUsers,
            'light_users' => $lightUsers,
            'users' => $users,
            'tier_up_to' => $tier['up_to'],
            'workers' => $tier['workers'],
            'storage_gb' => $storageGb,
            'storage_capped' => $storageWanted > $storageGb ? 1 : 0,
            'full_unit' => $fullUnit,
            'light_unit' => $lightUnit,
            'full_month' => $full,
            'light_month' => $light,
            'license_month' => $full + $light,
            'worker_unit' => $workerUnit,
            'storage_unit' => $storageUnit,
            'staging_unit' => $stagingUnit,
            'worker_month' => $worker,
            'storage_month' => $storage,
            'staging_month' => $stagingTotal,
            'hosting_month' => $hosting,
            'total_month' => $full + $light + $hosting,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function calculatorConfig(): array
    {
        $keys = ['eur_to_chf', 'license', 'hosting', 'tiers', 'storage_gb_per_user', 'storage_gb_max', 'max_users', 'min_users', 'full_users', 'light_users', 'staging'];

        return array_combine($keys, array_map(fn (string $key): mixed => config('odoo-pricing.'.$key), $keys));
    }

    public static function headline(int $fullUsers, int $lightUsers): string
    {
        $parts = array_filter([
            $fullUsers > 0 ? trans_choice('components.odoo_pricing.report.full', $fullUsers) : null,
            $lightUsers > 0 ? trans_choice('components.odoo_pricing.report.light', $lightUsers) : null,
        ]);

        return implode(' '.__('components.odoo_pricing.report.and').' ', $parts);
    }

    public static function format(int $cents): string
    {
        return 'CHF '.number_format($cents / 100, 2, '.', "'");
    }

    /**
     * @return array{up_to: int, workers: int}
     */
    private static function tier(int $users): array
    {
        /** @var array<int, array{up_to: int, workers: int}> $tiers */
        $tiers = config()->array('odoo-pricing.tiers');

        return collect($tiers)->first(fn (array $tier): bool => $users <= $tier['up_to'])
            ?? ['up_to' => $users, 'workers' => 1];
    }
}
