<?php

declare(strict_types=1);

use App\Models\InfrastructureConnection;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use App\Models\InfrastructureStack;
use App\Support\Infrastructure\Catalog;
use Database\Seeders\InfrastructureSeeder;

use function Pest\Laravel\seed;

beforeEach(function () {
    seed(InfrastructureSeeder::class);
});

/**
 * @param  list<string>  $services
 * @param  array<string, array<string, mixed>>  $overrides
 */
function stackOf(array $services, array $overrides = []): InfrastructureStack
{
    return InfrastructureStack::factory()->make(['services' => $services, 'overrides' => $overrides]);
}

/**
 * @param  array<string, array<string, mixed>>  $overrides
 * @return array{id: string, name: string, text: string, provider: string, place: string, countries: list<array{code: string, name: string}>, flags: list<string>, vendor: string, vendor_country: array{code: string, name: string}|null, operation: string, links: list<array{label: string, url: string, host: string}>}
 */
function stationOf(string $key, array $overrides = []): array
{
    return Catalog::layers(stackOf([$key], $overrides))[0]['stations'][0];
}

it('builds every layer in order for a stack that uses everything', function () {
    /** @var list<string> $all */
    $all = InfrastructureService::query()->pluck('key')->all();
    $layers = Catalog::layers(stackOf($all));

    expect(array_column($layers, 'key'))->toBe(InfrastructureLayer::query()->orderBy('order')->pluck('key')->all())
        ->and(array_sum(array_map(fn (array $layer): int => count($layer['stations']), $layers)))->toBe(count($all));

    foreach ($layers as $layer) {
        foreach ($layer['stations'] as $station) {
            expect($station['name'])->not->toBeEmpty()
                ->and($station['text'])->not->toBeEmpty()
                ->and($station['operation'])->not->toStartWith('components.')
                ->and($station['flags'])->not->toBeEmpty();
        }
    }
})->group('unit', 'infrastructure');

it('keeps the order of the catalog, not the order of the stack', function () {
    $layers = Catalog::layers(stackOf(['db', 'waf', 'app', 'domain']));

    expect(array_column($layers, 'key'))->toBe(['network', 'platform'])
        ->and(array_column($layers[0]['stations'], 'id'))->toBe(['domain', 'waf'])
        ->and(array_column($layers[1]['stations'], 'id'))->toBe(['app', 'db']);
})->group('unit', 'infrastructure');

it('drops layers without services, unknown services and connections without both ends', function () {
    $layers = Catalog::layers(stackOf(['domain', 'app', 'db', 'unknown', 'domain']));

    expect(array_column($layers, 'key'))->toBe(['network', 'platform'])
        ->and(Catalog::edges($layers))->toBe([['req', 'domain', 'constant'], ['app', 'db', 'constant']]);
})->group('unit', 'infrastructure');

it('returns nothing for an empty stack or an empty catalog', function () {
    expect(Catalog::layers(stackOf([])))->toBe([])
        ->and(Catalog::edges([]))->toBe([]);

    InfrastructureLayer::query()->delete();
    InfrastructureConnection::query()->delete();

    expect(Catalog::layers(stackOf(['domain'])))->toBe([])
        ->and(Catalog::edges([]))->toBe([]);
})->group('unit', 'infrastructure');

it('translates names, texts, layers and the operation', function (string $locale, string $layer, string $operation) {
    app()->setLocale($locale);

    $layers = Catalog::layers(stackOf(['domain']));

    expect($layers[0]['name'])->toBe($layer)
        ->and($layers[0]['stations'][0]['operation'])->toBe($operation)
        ->and($layers[0]['stations'][0]['countries'][0]['name'])->toBe($locale === 'de_CH' ? 'Schweiz' : 'Switzerland');
})->with([
    ['de_CH', 'Netzwerk', 'Eingekaufter Service'],
    ['en_CH', 'Network', 'Purchased service'],
])->group('unit', 'infrastructure');

it('shows the provider country for worldwide services', function () {
    $dns = stationOf('dns');

    expect($dns['countries'])->toBe([['code' => 'WW', 'name' => 'Weltweit']])
        ->and($dns['flags'])->toBe(['BG'])
        ->and($dns['vendor_country'])->toBe(['code' => 'BG', 'name' => 'Bulgarien'])
        ->and($dns['links'][0])->toBe(['label' => 'Anbieter-Website', 'url' => 'https://www.cloudns.net', 'host' => 'cloudns.net']);
})->group('unit', 'infrastructure');

it('keeps the worldwide flag when the provider has no country', function () {
    InfrastructureService::query()->where('key', 'dns')->update(['vendor_country' => null]);

    $dns = stationOf('dns');

    expect($dns['flags'])->toBe(['WW'])
        ->and($dns['vendor_country'])->toBeNull();
})->group('unit', 'infrastructure');

it('applies overrides for provider, place, countries and text', function () {
    $spaces = stationOf('spaces', ['spaces' => [
        'provider' => 'Cloudflare R2',
        'place' => 'Zürich',
        'countries' => ['CH', 7, 'DE'],
        'text' => ['de_CH' => 'Eigener Text.'],
    ]]);

    expect($spaces['provider'])->toBe('Cloudflare R2')
        ->and($spaces['vendor'])->toBe('Cloudflare R2')
        ->and($spaces['vendor_country'])->toBeNull()
        ->and($spaces['links'])->toBe([])
        ->and($spaces['place'])->toBe('Zürich')
        ->and(array_column($spaces['countries'], 'code'))->toBe(['CH', 'DE'])
        ->and($spaces['flags'])->toBe(['CH', 'DE'])
        ->and($spaces['text'])->toBe('Eigener Text.');
})->group('unit', 'infrastructure');

it('ignores overrides of the wrong type and texts for another language', function () {
    $base = stationOf('spaces');
    $spaces = stationOf('spaces', ['spaces' => ['provider' => 5, 'place' => null, 'countries' => 'CH', 'text' => ['en_CH' => 'English only.']]]);

    expect($spaces)->toBe($base);
})->group('unit', 'infrastructure');

it('falls back to the country code when a name is missing', function () {
    $domain = stationOf('domain', ['domain' => ['countries' => ['XX']]]);

    expect($domain['countries'])->toBe([['code' => 'XX', 'name' => 'XX']]);
})->group('unit', 'infrastructure');

it('strips protocol, www and the trailing slash from link hosts', function () {
    InfrastructureService::query()->where('key', 'domain')->sole()->update(['links' => [
        ['type' => 'website', 'url' => 'http://www.example.com/'],
        ['type' => 'location', 'url' => 'https://docs.example.com/regions/eu/'],
    ]]);

    expect(array_column(stationOf('domain')['links'], 'host'))->toBe(['example.com', 'docs.example.com/regions/eu']);
})->group('unit', 'infrastructure');

it('orders countries Switzerland first, then Europe, then the USA', function () {
    $stack = InfrastructureStack::factory()->create([
        'services' => ['app'],
        'overrides' => ['app' => ['countries' => ['US', 'SE', 'CH']]],
    ]);

    $app = Catalog::layers($stack)[0]['stations'][0];

    expect(array_column($app['countries'], 'code'))->toBe(['CH', 'SE', 'US'])
        ->and($app['flags'])->toBe(['CH', 'SE', 'US']);
});
