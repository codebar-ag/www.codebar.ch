<?php

declare(strict_types=1);

namespace App\Seo;

/**
 * Typed access to config/company.php.
 *
 * The config file is a plain array, so every read out of it is `mixed`. This
 * wrapper does the narrowing once instead of at each of the two dozen call
 * sites in SchemaGraph and SchemaNodes.
 */
class Company
{
    public static function legalName(): string
    {
        return config()->string('company.legal_name');
    }

    /**
     * @return array<int, string>
     */
    public static function alternateNames(): array
    {
        return self::strings('company.alternate_names');
    }

    public static function uid(): string
    {
        return config()->string('company.uid');
    }

    public static function numberOfEmployees(): int
    {
        return config()->integer('company.number_of_employees');
    }

    /**
     * @return array<int, string>
     */
    public static function knowsAbout(): array
    {
        return self::strings('company.knows_about');
    }

    public static function email(): string
    {
        return config()->string('company.email');
    }

    public static function phone(): string
    {
        return config()->string('company.phone.e164');
    }

    public static function logo(): string
    {
        return config()->string('company.logo');
    }

    /**
     * Verified profiles on other platforms — schema.org sameAs.
     *
     * @return array<int, string>
     */
    public static function sameAs(): array
    {
        return self::strings('company.same_as');
    }

    /**
     * @return array<int, array{key: string, primary: bool, city: string, label: string, street: string, postal_code: string, country: string, map_url: string}>
     */
    public static function locations(): array
    {
        /** @var array<int, array{key: string, primary: bool, city: string, label: string, street: string, postal_code: string, country: string, map_url: string}> $locations */
        $locations = config()->array('company.locations');

        return $locations;
    }

    /**
     * @return array{key: string, primary: bool, city: string, label: string, street: string, postal_code: string, country: string, map_url: string}|null
     */
    public static function primaryLocation(): ?array
    {
        $locations = self::locations();

        foreach ($locations as $location) {
            if ($location['primary']) {
                return $location;
            }
        }

        return $locations[0] ?? null;
    }

    /**
     * `open`/`close` are null on days the office is closed.
     *
     * @return array<int, array{day: string, open: ?string, close: ?string}>
     */
    public static function openingHours(): array
    {
        /** @var array<int, array{day: string, open: ?string, close: ?string}> $hours */
        $hours = config()->array('company.opening_hours');

        return $hours;
    }

    /**
     * Site root without a trailing slash — the base for every absolute URL and
     * schema.org @id we emit.
     */
    public static function baseUrl(): string
    {
        return rtrim(config()->string('app.url'), '/');
    }

    /**
     * @return array<int, string>
     */
    private static function strings(string $key): array
    {
        return array_values(array_filter(
            config()->array($key),
            fn (mixed $value): bool => is_string($value) && $value !== '',
        ));
    }
}
