<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array<string, int> $prices
 */
class OdooPriceSnapshot extends Model
{
    public const array KEYS = [
        'license.full.yearly',
        'license.full.monthly',
        'license.light.yearly',
        'hosting.worker',
        'hosting.staging',
        'hosting.storage_gb',
        'hosting.factor.yearly',
    ];

    /** @var list<string> */
    protected $fillable = ['prices'];

    protected $casts = [
        'prices' => 'array',
    ];

    /**
     * @return array<string, int>
     */
    public static function configured(): array
    {
        $prices = [];

        foreach (self::KEYS as $key) {
            $value = config('odoo-pricing.'.$key);
            $number = is_numeric($value) ? (float) $value : 0.0;

            $prices[$key] = (int) round($key === 'hosting.factor.yearly' ? $number * 100 : $number);
        }

        return $prices;
    }

    public static function apply(): ?self
    {
        $snapshot = self::query()->latest('id')->first();

        if (! $snapshot instanceof self) {
            return null;
        }

        foreach (self::KEYS as $key) {
            $value = $snapshot->prices[$key] ?? null;

            if (! is_int($value)) {
                continue;
            }

            config()->set('odoo-pricing.'.$key, $key === 'hosting.factor.yearly' ? $value / 100 : $value);
        }

        config()->set('odoo-pricing.license.light.monthly', config('odoo-pricing.license.light.yearly'));

        if ($snapshot->updated_at !== null) {
            config()->set('odoo-pricing.checked_at', $snapshot->updated_at->toDateString());
        }

        return $snapshot;
    }
}
