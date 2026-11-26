<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use App\Models\InfrastructureStack;
use Database\Seeders\InfrastructureSeeder;
use Database\Seeders\PagesTableSeeder;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Laravel\withSession;

beforeEach(function () {
    seed(InfrastructureSeeder::class);
});

/**
 * @return array{layers: list<array{key: string, stations: list<array{id: string, provider: string}>}>, edges: list<array{0: string, 1: string, 2: string}>, urls: array<string, string>, auto: bool, site: array{image: string|null, domain: string}, labels: array<string, string>}
 */
function infrastructureData(string $url): array
{
    preg_match('/<script type="application\/json" id="infraData">(.*?)<\/script>/s', (string) get($url)->assertOk()->getContent(), $match);

    /** @var array{layers: list<array{key: string, stations: list<array{id: string, provider: string}>}>, edges: list<array{0: string, 1: string, 2: string}>, urls: array<string, string>, auto: bool, site: array{image: string|null, domain: string}, labels: array<string, string>} $data */
    $data = json_decode($match[1] ?? '', true, flags: JSON_THROW_ON_ERROR);

    return $data;
}

function exampleStack(): InfrastructureStack
{
    return InfrastructureStack::factory()->create([
        'slug' => 'web.example',
        'name' => 'Example',
        'domain' => 'www.example.ch',
        'sketch' => '<svg xmlns="http://www.w3.org/2000/svg"/>',
        'services' => ['domain', 'dns', 'app', 'db', 'spaces', 'unknown'],
        'overrides' => ['spaces' => ['provider' => 'Cloudflare R2', 'place' => '', 'countries' => ['WW']]],
    ]);
}

it('lists every service of the default stack with area filters and the graphic', function (string $locale) {
    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.infrastructure.index'))
        ->assertOk()
        ->assertSee(__('components.infrastructure.title', locale: $locale))
        ->assertSee('id="infraData"', false)
        ->assertSee('id="sxWorld"', false)
        ->assertSee('id="infraOverlay"', false)
        ->assertSee('data-infra-filter-reset', false)
        ->assertSee('data-infra-filter="network"', false)
        ->assertSee('data-infra-layer="network"', false)
        ->assertSee('data-infra-filter="backups"', false)
        ->assertSee('data-infra-layer="backups"', false)
        ->assertSee('data-infra-filter="ai"', false)
        ->assertSee(__('components.infrastructure.filter_all', locale: $locale))
        ->assertSee('data-infra-open', false)
        ->assertSee(__('components.infrastructure.open_graphic', locale: $locale))
        ->assertSee('"auto":false', false)
        ->assertSee('Hosttech')
        ->assertSee('ClouDNS')
        ->assertSee('Laravel Cloud')
        ->assertSee('Postmark')
        ->assertSee('href="'.route(Str::slug($locale).'.infrastructure.show', ['station' => 'vimeo']).'"', false)
        ->assertDontSee('data-station="vimeo"', false);
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('infrastructure');

it('links the page from the company column of the footer', function () {
    get(route('de-ch.start.index'))
        ->assertOk()
        ->assertSee(route('de-ch.infrastructure.index'), false);
})->group('infrastructure');

it('carries its own metadata and is listed in the sitemap', function () {
    seed(PagesTableSeeder::class);

    get(route('de-ch.infrastructure.index'))
        ->assertOk()
        ->assertSee('<title>Infrastruktur und Dienste hinter unseren Anwendungen', false)
        ->assertSee('name="robots" content="index,follow"', false)
        ->assertSee('hreflang="en-CH" href="'.route('en-ch.infrastructure.index').'"', false);

    get('sitemap.xml')
        ->assertOk()
        ->assertSee(route('de-ch.infrastructure.index'), false);
})->group('infrastructure');

it('lists the detail pages of the default stack in the sitemap in both languages', function () {
    exampleStack();

    $xml = (string) get('sitemap.xml')->assertOk()->getContent();

    expect(substr_count($xml, '<loc>'.route('de-ch.infrastructure.show', ['station' => 'vimeo']).'</loc>'))->toBe(1)
        ->and(substr_count($xml, '<loc>'.route('en-ch.infrastructure.show', ['station' => 'vimeo']).'</loc>'))->toBe(1)
        ->and(preg_match_all('#<loc>[^<]*/infrastruktur/[a-z]+</loc>#', $xml))->toBe(24)
        ->and($xml)->toContain('/infrastruktur/azure')
        ->and($xml)->not->toContain('kunde=')
        ->and($xml)->not->toContain('web.example');
})->group('infrastructure');

it('leaves the detail pages out of the sitemap while no default stack exists', function () {
    InfrastructureStack::query()->delete();

    expect((string) get('sitemap.xml')->assertOk()->getContent())->not->toContain('/infrastruktur/');
})->group('infrastructure');

it('hands the graphic the layers, connections, cover, labels and detail links', function () {
    $data = infrastructureData(route('de-ch.infrastructure.index'));

    expect(array_column($data['layers'], 'key'))->toBe(['network', 'platform', 'services', 'ai', 'monitoring', 'development', 'backups'])
        ->and($data['edges'])->toContain(['req', 'domain', 'constant'])
        ->and($data['edges'])->toContain(['app', 'azure', 'occasional'])
        ->and($data['urls']['app'])->toBe(route('de-ch.infrastructure.show', ['station' => 'app']))
        ->and($data['auto'])->toBeFalse()
        ->and($data['site']['domain'])->toBe('www.codebar.ch')
        ->and($data['site']['image'])->toEndWith('images/infrastructure/front-codebar.webp')
        ->and(array_keys($data['labels']))->toBe(['back', 'progress', 'close', 'processing', 'provider', 'origin', 'operation', 'details']);
})->group('infrastructure');

it('applies the override of the default stack', function () {
    $data = infrastructureData(route('de-ch.infrastructure.index'));
    $platform = collect($data['layers'])->firstWhere('key', 'platform');
    $cache = collect($platform['stations'] ?? [])->firstWhere('id', 'cache');

    expect($cache['provider'] ?? null)->toBe('Neon');
})->group('infrastructure');

it('keeps script-breaking characters out of the data block', function () {
    InfrastructureService::query()->where('key', 'domain')->update(['provider' => '</script><b>&"\'']);

    $content = (string) get(route('de-ch.infrastructure.index'))->assertOk()->getContent();
    preg_match('/<script type="application\/json" id="infraData">(.*?)<\/script>/s', $content, $match);
    /** @var array{layers: list<array{stations: list<array{provider: string}>}>} $data */
    $data = json_decode($match[1] ?? '', true, flags: JSON_THROW_ON_ERROR);

    expect($match[1] ?? '')->not->toContain('</script><b>')
        ->and($data['layers'][0]['stations'][0]['provider'])->toBe('</script><b>&"\'')
        ->and($content)->toContain('&lt;/script&gt;&lt;b&gt;');
})->group('infrastructure');

it('shows a customer stack for a known slug and keeps it out of the index', function () {
    seed(PagesTableSeeder::class);
    exampleStack();

    get(route('de-ch.infrastructure.index', ['kunde' => 'web.example']))
        ->assertOk()
        ->assertSee('name="robots" content="noindex,nofollow"', false)
        ->assertSee('data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg"/>'), false)
        ->assertSee('www.example.ch')
        ->assertSee('Cloudflare R2')
        ->assertSee('"auto":true', false)
        ->assertSee('href="'.e(route('de-ch.infrastructure.show', ['station' => 'spaces', 'kunde' => 'web.example'])).'"', false)
        ->assertSee('data-infra-filter="services"', false)
        ->assertDontSee('data-infra-filter="monitoring"', false)
        ->assertDontSee(route('de-ch.infrastructure.show', ['station' => 'postmark']), false)
        ->assertDontSee('/unknown', false);
})->group('infrastructure');

it('shows the customer provider on the detail page and keeps the customer in the links', function (string $locale) {
    exampleStack();
    $prefix = Str::slug($locale);

    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route($prefix.'.infrastructure.show', ['station' => 'spaces', 'kunde' => 'web.example']))
        ->assertOk()
        ->assertSee('name="robots" content="noindex,nofollow"', false)
        ->assertSee('Cloudflare R2')
        ->assertDontSee('DigitalOcean')
        ->assertSee(e(route($prefix.'.infrastructure.index', ['kunde' => 'web.example'])), false);
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('infrastructure');

it('answers 404 on the detail page for a service the customer does not use or an unknown customer', function (string $station, string $slug) {
    exampleStack();

    get(route('de-ch.infrastructure.show', ['station' => $station, 'kunde' => $slug]))->assertNotFound();
})->with([
    ['postmark', 'web.example'],
    ['unknown', 'web.example'],
    ['spaces', 'web.missing'],
    ['spaces', '../.env'],
])->group('infrastructure');

it('answers 404 for an unknown or malformed slug', function (string $slug) {
    get(route('de-ch.infrastructure.index', ['kunde' => $slug]))->assertNotFound();
})->with(['web.missing', '../.env', 'UPPER', 'a', '.hidden', 'web example', str_repeat('a', 62)])->group('infrastructure');

it('treats an empty or non-text customer parameter as the public view', function (string $query) {
    get(route('de-ch.infrastructure.index').$query)
        ->assertOk()
        ->assertSee('"auto":false', false)
        ->assertSee('www.codebar.ch');
})->with(['?kunde=', '?kunde[]=web.example', '?other=1'])->group('infrastructure');

it('shows a customer stack without a sketch and without services', function () {
    InfrastructureStack::factory()->create(['slug' => 'web.empty', 'domain' => 'www.empty.ch', 'services' => []]);

    $data = infrastructureData(route('de-ch.infrastructure.index', ['kunde' => 'web.empty']));

    expect($data['layers'])->toBe([])
        ->and($data['edges'])->toBe([])
        ->and($data['urls'])->toBe([])
        ->and($data['site']['image'])->toBeNull()
        ->and($data['site']['domain'])->toBe('www.empty.ch');
})->group('infrastructure');

it('answers 404 while no default stack exists', function () {
    InfrastructureStack::query()->delete();

    get(route('de-ch.infrastructure.index'))->assertNotFound();
    get(route('de-ch.infrastructure.show', ['station' => 'app']))->assertNotFound();
})->group('infrastructure');

it('shows a detail page for a service of the default stack', function (string $locale) {
    $service = InfrastructureService::query()->where('key', 'vimeo')->sole();
    /** @var string $name */
    $name = $service->getTranslation('name', $locale);
    /** @var string $text */
    $text = $service->getTranslation('text', $locale);
    /** @var string $layer */
    $layer = InfrastructureLayer::query()->where('key', 'services')->sole()->getTranslation('name', $locale);

    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.infrastructure.show', ['station' => 'vimeo']))
        ->assertOk()
        ->assertSee('<title>'.$name.' · Vimeo', false)
        ->assertSee($text)
        ->assertSee($layer)
        ->assertSee(__('components.infrastructure.operation_service', locale: $locale))
        ->assertSee('name="robots" content="index,follow"', false)
        ->assertSee('vimeo.com')
        ->assertSee('target="_blank"', false)
        ->assertSee(route(Str::slug($locale).'.infrastructure.index'), false)
        ->assertSee('hreflang="'.($locale === LocaleEnum::DE->value ? 'en-CH' : 'de-CH').'"', false);
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('infrastructure');

it('shows every service of the default stack on its own page', function () {
    $stack = InfrastructureStack::default();

    foreach ($stack->services ?? [] as $key) {
        get(route('de-ch.infrastructure.show', ['station' => $key]))->assertOk();
    }

    expect($stack?->services)->toHaveCount(24);
})->group('infrastructure');

it('answers 404 for a service that does not exist', function (string $path) {
    get('/infrastruktur/'.$path)->assertNotFound();
})->with(['nothing', 'APP', 'app1', 'app/extra'])->group('infrastructure');

it('lists the certificate, gives it a detail page and labels own operation without the company name', function () {
    get(localized_route('infrastructure.index'))
        ->assertOk()
        ->assertSee('Zertifikat')
        ->assertSee('Google Trust Services')
        ->assertDontSee('Self-hosted bei codebar');

    get(localized_route('infrastructure.show', ['station' => 'tls']))
        ->assertOk()
        ->assertSee('Google Trust Services');

    expect(__('components.infrastructure.operation_self'))->toBe('Self-hosted');
});

it('shows the issuer a customer stack overrides for the certificate', function () {
    InfrastructureStack::factory()->create([
        'slug' => 'portal.example',
        'services' => ['domain', 'tls', 'app'],
        'overrides' => ['tls' => ['provider' => "Let's Encrypt"]],
    ]);

    get(localized_route('infrastructure.show', ['station' => 'tls']).'?kunde=portal.example')
        ->assertOk()
        ->assertSee("Let's Encrypt", false)
        ->assertDontSee('Google Trust Services');
});

it('shows every service on the public page even when the default stack lists none', function () {
    InfrastructureStack::query()->where('is_default', true)->update(['services' => []]);

    $response = get(localized_route('infrastructure.index'))->assertOk();

    foreach (['Feedback', 'Analytics', 'Zertifikat'] as $name) {
        $response->assertSee($name);
    }

    get(route('de-ch.infrastructure.show', ['station' => 'feedback']))->assertOk();
})->group('infrastructure');
