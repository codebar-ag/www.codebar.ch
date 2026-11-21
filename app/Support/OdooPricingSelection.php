<?php

declare(strict_types=1);

namespace App\Support;

final readonly class OdooPricingSelection
{
    public const array KEYS = ['full', 'light', 'billing'];

    public const array BILLING = ['yearly', 'monthly'];

    private function __construct(
        public int $full,
        public int $light,
        public string $billing,
    ) {}

    public static function defaults(): self
    {
        return self::normalized(
            config()->integer('odoo-pricing.full_users.default'),
            config()->integer('odoo-pricing.light_users.default'),
            self::BILLING[0],
        );
    }

    /**
     * @param  array<array-key, mixed>  $query
     */
    public static function fromQuery(array $query): self
    {
        $defaults = self::defaults();
        $billing = $query['billing'] ?? null;

        return self::normalized(
            self::count($query['full'] ?? null) ?? $defaults->full,
            self::count($query['light'] ?? null) ?? $defaults->light,
            is_string($billing) && in_array($billing, self::BILLING, true) ? $billing : $defaults->billing,
        );
    }

    /**
     * @param  array<array-key, mixed>  $query
     */
    public static function isRequested(array $query): bool
    {
        return array_intersect(self::KEYS, array_keys($query)) !== [];
    }

    /**
     * @param  array<array-key, mixed>  $query
     */
    public function matches(array $query): bool
    {
        foreach ($this->query() as $key => $value) {
            if (($query[$key] ?? null) !== (string) $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array{full: int, light: int, billing: string}
     */
    public function query(): array
    {
        return ['full' => $this->full, 'light' => $this->light, 'billing' => $this->billing];
    }

    /**
     * @return array<string, int>
     */
    public function result(): array
    {
        return OdooPricing::calculate($this->full, $this->light, $this->billing);
    }

    private static function normalized(int $full, int $light, string $billing): self
    {
        $result = OdooPricing::calculate($full, $light, $billing);

        return new self($result['full_users'], $result['light_users'], $billing);
    }

    private static function count(mixed $value): ?int
    {
        if (! is_string($value) || preg_match('/^\d{1,4}$/', $value) !== 1) {
            return null;
        }

        return (int) $value;
    }
}
