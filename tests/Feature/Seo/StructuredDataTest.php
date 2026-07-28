<?php

use App\Models\News;
use App\Models\Page;
use Database\Seeders\ContactsTableSeeder;
use Database\Seeders\PagesTableSeeder;
use Database\Seeders\ServicesTableSeeder;
use Illuminate\Support\Collection;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

/**
 * Extracts and decodes the JSON-LD graph from a rendered page.
 *
 * Decoding rather than string-matching is the point: a malformed payload is
 * silently ignored by Google, so the test has to fail on invalid JSON.
 *
 * @return array<int, array<string, mixed>>
 */
function schemaGraph(string $url): array
{
    $html = (string) get($url)->assertOk()->getContent();

    expect($html)->toContain('application/ld+json');

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    $decoded = json_decode($matches[1] ?? '', true);

    expect(json_last_error())->toBe(JSON_ERROR_NONE, 'JSON-LD payload is not valid JSON');
    expect($decoded)->toBeArray()->toHaveKey('@context', 'https://schema.org');

    return nodeList(is_array($decoded) ? ($decoded['@graph'] ?? null) : null)->all();
}

/**
 * Narrows an untyped JSON value to a list of schema nodes.
 *
 * @return Collection<int, array<string, mixed>>
 */
function nodeList(mixed $value): Collection
{
    expect($value)->toBeArray();

    /** @var array<int, array<string, mixed>> $value */
    return collect($value)->values();
}

/**
 * Finds the single node of a given @type, failing the test when it is absent —
 * so the assertions that follow never have to guard against null.
 *
 * @param  array<int, array<string, mixed>>  $graph
 * @return array<string, mixed>
 */
function schemaNode(array $graph, string $type): array
{
    $node = collect($graph)->firstWhere('@type', $type);

    expect($node)->toBeArray("no {$type} node in the JSON-LD graph");

    /** @var array<string, mixed> $node */
    return $node;
}

/**
 * All nodes of a given @type.
 *
 * @param  array<int, array<string, mixed>>  $graph
 * @return Collection<int, array<string, mixed>>
 */
function schemaNodes(array $graph, string $type): Collection
{
    return collect($graph)->where('@type', $type)->values();
}

/**
 * @param  array<string, mixed>  $breadcrumb
 * @return Collection<int, array<string, mixed>>
 */
function breadcrumbItems(array $breadcrumb): Collection
{
    return nodeList(data_get($breadcrumb, 'itemListElement'));
}

beforeEach(function () {
    seed(PagesTableSeeder::class);
});

it('describes the organization on every page', function () {
    $organization = schemaNode(schemaGraph(route('de-ch.start.index')), 'Organization');

    expect(data_get($organization, 'name'))->toBe(config('company.legal_name'))
        ->and(data_get($organization, 'vatID'))->toBe(config('company.uid'))
        ->and(data_get($organization, 'telephone'))->toBe(config('company.phone.e164'))
        ->and(data_get($organization, 'address.streetAddress'))->toBe('Hauptstrasse 91')
        ->and(data_get($organization, 'address.addressLocality'))->toBe('Zunzgen')
        // sameAs is what ties the site to the company as a known entity.
        ->and(data_get($organization, 'sameAs'))->toContain('https://github.com/codebar-ag');
})->group('seo', 'schema');

it('declares both the full legal name and the short brand', function () {
    // Both spellings have to be in the graph: "codebar Solutions AG" is what
    // Zefix, LinkedIn and the Business Profiles say, "codebar" is what people
    // actually type. alternateName is how Google learns they are one entity.
    $graph = schemaGraph(route('de-ch.start.index'));

    foreach (['Organization', 'WebSite'] as $type) {
        $node = schemaNode($graph, $type);

        expect(data_get($node, 'name'))->toBe('codebar Solutions AG')
            ->and(data_get($node, 'alternateName'))->toContain('codebar')
            ->and(data_get($node, 'alternateName'))->toContain('codebar Solutions');
    }
})->group('seo', 'schema');

it('carries the full company name in every indexable page title', function () {
    // A title without the brand wastes the strongest signal a search result has.
    // Collected rather than asserted one by one so a failure names every offender.
    $missing = [];

    Page::all()->each(function (Page $page) use (&$missing): void {
        foreach (['de_CH', 'en_CH'] as $locale) {
            $title = $page->getTranslation('title', $locale);

            if (is_string($title) && $title !== '' && ! str_contains($title, 'codebar Solutions AG')) {
                $missing[] = "{$page->key} [{$locale}]: {$title}";
            }
        }
    });

    expect($missing)->toBe([]);
})->group('seo');

it('links WebSite and WebPage back to the organization by id', function () {
    $graph = schemaGraph(route('de-ch.services.index'));

    $organizationId = data_get(schemaNode($graph, 'Organization'), '@id');

    expect(data_get(schemaNode($graph, 'WebSite'), 'publisher.@id'))->toBe($organizationId)
        ->and(data_get(schemaNode($graph, 'WebPage'), 'about.@id'))->toBe($organizationId)
        ->and(data_get(schemaNode($graph, 'WebPage'), 'url'))->toBe(route('de-ch.services.index'));
})->group('seo', 'schema');

it('omits the breadcrumb on the start page', function () {
    // A single-item trail carries no information.
    expect(schemaNodes(schemaGraph(route('de-ch.start.index')), 'BreadcrumbList'))->toBeEmpty();
})->group('seo', 'schema');

it('builds a breadcrumb from real pages only', function () {
    $breadcrumb = schemaNode(schemaGraph(route('de-ch.ai.llm.index')), 'BreadcrumbList');

    $items = breadcrumbItems($breadcrumb);

    expect($items)->toHaveCount(3)
        ->and($items->pluck('item')->all())->toBe([
            route('de-ch.start.index'),
            route('de-ch.ai.index'),
            route('de-ch.ai.llm.index'),
        ])
        ->and($items->pluck('position')->all())->toBe([1, 2, 3]);
})->group('seo', 'schema');

it('drops breadcrumb segments that are not routable', function () {
    // /rechtliches is a path prefix, not a page — linking to it would send
    // users and crawlers to a 404.
    $breadcrumb = schemaNode(schemaGraph(route('de-ch.legal.imprint.index')), 'BreadcrumbList');

    expect(breadcrumbItems($breadcrumb)->pluck('item')->all())->toBe([
        route('de-ch.start.index'),
        route('de-ch.legal.imprint.index'),
    ]);
})->group('seo', 'schema');

it('publishes both locations with opening hours on the contact page', function () {
    $graph = schemaGraph(route('de-ch.contact.index'));

    $businesses = schemaNodes($graph, 'ProfessionalService');

    expect($businesses)->toHaveCount(2)
        ->and($businesses->pluck('address.addressLocality')->all())->toBe(['Zunzgen', 'Oberwil']);

    $hours = nodeList(data_get($businesses->first(), 'openingHoursSpecification'));

    // Sunday is closed, so it must be absent rather than present with nulls.
    expect($hours)->toHaveCount(6)
        ->and($hours->pluck('dayOfWeek')->all())->not->toContain('https://schema.org/Sunday')
        ->and(data_get($hours->first(), 'opens'))->toBe('08:00');
})->group('seo', 'schema');

it('describes the team as people on the about page', function () {
    seed(ContactsTableSeeder::class);

    $profile = schemaNode(schemaGraph(route('de-ch.about-us.index')), 'ProfilePage');

    expect(data_get($profile, 'mainEntity'))->not->toBeEmpty()
        ->and(data_get($profile, 'mainEntity.0.@type'))->toBe('Person')
        ->and(data_get($profile, 'mainEntity.0.name'))->not->toBeEmpty();
})->group('seo', 'schema');

it('describes what the company sells on the expertise page', function () {
    seed(ServicesTableSeeder::class);

    $services = schemaNodes(schemaGraph(route('de-ch.services.index')), 'Service');

    expect($services)->not->toBeEmpty()
        ->and($services->first())->toHaveKeys(['name', 'description', 'provider']);
})->group('seo', 'schema');

it('marks up a news article as a BlogPosting', function () {
    $news = News::factory()->create();

    $article = schemaNode(
        schemaGraph(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug])),
        'BlogPosting'
    );

    expect($article)->toHaveKeys(['headline', 'datePublished', 'author', 'publisher'])
        ->and(data_get($article, 'inLanguage'))->toBe('de-CH');
})->group('seo', 'schema');

it('escapes a closing script tag in the payload', function () {
    // Content is authored by us, but a stray "</script>" in a title would still
    // break out of the data block and truncate the page.
    $news = News::factory()->create([
        'title' => ['de_CH' => 'Test </script> Titel', 'en_CH' => 'Test </script> title'],
    ]);

    $html = get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();

    expect($html)->toContain('<\/script>');

    // The document must still be intact: the closing </head> follows the block.
    expect($html)->toContain('</head>');
})->group('seo', 'schema');
