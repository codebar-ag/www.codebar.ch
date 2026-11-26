<?php

declare(strict_types=1);

namespace App\Support\Infrastructure;

use App\Models\InfrastructureConnection;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use App\Models\InfrastructureStack;

/**
 * @phpstan-type Station array{id: string, name: string, text: string, provider: string, place: string, countries: list<array{code: string, name: string}>, flags: list<string>, vendor: string, vendor_country: array{code: string, name: string}|null, operation: string, links: list<array{label: string, url: string, host: string}>}
 * @phpstan-type Layer array{key: string, name: string, color: string, stations: list<Station>}
 */
class Catalog
{
    /**
     * @return list<Layer>
     */
    public static function layers(InfrastructureStack $stack): array
    {
        $result = [];

        foreach (InfrastructureLayer::query()->with('services')->orderBy('order')->orderBy('id')->get() as $layer) {
            $stations = [];

            foreach ($layer->services as $service) {
                if ($stack->is_default || in_array($service->key, $stack->services, true)) {
                    $stations[] = self::station($service, $stack->overrides[$service->key] ?? []);
                }
            }

            if ($stations !== []) {
                $result[] = [
                    'key' => $layer->key,
                    'name' => $layer->name,
                    'color' => $layer->color,
                    'stations' => $stations,
                ];
            }
        }

        return $result;
    }

    /**
     * @param  list<Layer>  $layers
     * @return list<array{0: string, 1: string, 2: string}>
     */
    public static function edges(array $layers): array
    {
        $ids = [InfrastructureConnection::REQUEST];

        foreach ($layers as $layer) {
            foreach ($layer['stations'] as $station) {
                $ids[] = $station['id'];
            }
        }

        $edges = [];

        foreach (InfrastructureConnection::query()->orderBy('order')->orderBy('id')->get() as $connection) {
            if (in_array($connection->from, $ids, true) && in_array($connection->to, $ids, true)) {
                $edges[] = [$connection->from, $connection->to, $connection->flow->value];
            }
        }

        return $edges;
    }

    /**
     * @param  array<string, mixed>  $override
     * @return Station
     */
    private static function station(InfrastructureService $service, array $override): array
    {
        $locale = app()->getLocale();
        $provider = is_string($override['provider'] ?? null) ? $override['provider'] : null;
        $countries = is_array($override['countries'] ?? null) ? array_values(array_filter($override['countries'], 'is_string')) : $service->countries;
        usort($countries, fn (string $a, string $b): int => self::rank($a) <=> self::rank($b));
        $text = $override['text'] ?? null;
        $vendorCountry = $service->vendor_country ?? '';

        return [
            'id' => $service->key,
            'name' => $service->name,
            'text' => is_array($text) && is_string($text[$locale] ?? null) ? $text[$locale] : $service->text,
            'provider' => $provider ?? $service->provider,
            'place' => is_string($override['place'] ?? null) ? $override['place'] : $service->place,
            'countries' => array_map(fn (string $code): array => ['code' => $code, 'name' => self::country($code)], $countries),
            'flags' => $countries === ['WW'] && $vendorCountry !== '' ? [$vendorCountry] : $countries,
            'vendor' => $provider ?? $service->vendor,
            'vendor_country' => $provider === null && $vendorCountry !== '' ? ['code' => $vendorCountry, 'name' => self::country($vendorCountry)] : null,
            'operation' => $service->operation->getLabel(),
            'links' => $provider !== null ? [] : array_map(fn (array $link): array => [
                'label' => (string) __('components.infrastructure.link_'.$link['type']),
                'url' => $link['url'],
                'host' => (string) preg_replace('#^https?://(www\.)?#', '', rtrim($link['url'], '/')),
            ], $service->links),
        ];
    }

    private static function rank(string $code): int
    {
        return match ($code) {
            'CH' => 0,
            'US' => 2,
            'WW' => 3,
            default => 1,
        };
    }

    private static function country(string $code): string
    {
        $key = 'components.infrastructure.countries.'.$code;
        $name = __($key);

        return is_string($name) && $name !== $key ? $name : $code;
    }
}
