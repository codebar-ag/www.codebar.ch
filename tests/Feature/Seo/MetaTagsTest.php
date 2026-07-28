<?php

use App\Models\Network;
use App\Models\News;
use Database\Seeders\PagesTableSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

function titleOf(string $html): string
{
    preg_match('#<title>(.*?)</title>#s', $html, $matches);

    return trim($matches[1] ?? '');
}

beforeEach(function () {
    seed(PagesTableSeeder::class);
});

it('renders the full set of meta tags on a page', function () {
    $html = (string) get(route('de-ch.services.index'))->assertOk()->getContent();

    expect($html)
        ->toContain('<title>')
        ->toContain('<meta name="description"')
        ->toContain('<meta name="robots" content="index,follow">')
        ->toContain('<link rel="canonical" href="'.route('de-ch.services.index').'">')
        ->toContain('<meta property="og:title"')
        ->toContain('<meta property="og:description"')
        ->toContain('<meta property="og:image"')
        ->toContain('<meta name="twitter:card" content="summary_large_image">');
})->group('seo');

it('pairs both locales with self-referencing hreflang links', function () {
    $html = (string) get(route('de-ch.services.index'))->assertOk()->getContent();

    expect($html)
        ->toContain('<link rel="alternate" hreflang="de-CH" href="'.route('de-ch.services.index').'">')
        ->toContain('<link rel="alternate" hreflang="en-CH" href="'.route('en-ch.services.index').'">')
        ->toContain('<link rel="alternate" hreflang="x-default"');
})->group('seo');

it('swaps the locale route parameter in a detail page hreflang', function () {
    // /news/{locale}/{slug} carries the locale twice: in the path prefix and as
    // a route parameter. Only swapping the prefix pointed the English alternate
    // at the German article — a wrong-language pair Google would ignore.
    $news = News::factory()->create();

    $html = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();

    expect($html)
        ->toContain('hreflang="en-CH" href="'.route('en-ch.news.show', ['locale' => 'en_CH', 'news' => $news->slug]).'"')
        ->toContain('hreflang="de-CH" href="'.route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]).'"');

    // The regression: the English path with the German locale parameter.
    expect($html)->not->toContain(
        'hreflang="en-CH" href="'.route('en-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]).'"'
    );
})->group('seo');

it('canonicalises away query parameters', function () {
    // The analytics page has GET filters; every combination must point back at
    // the bare URL rather than spawning near-duplicate indexable variants.
    $html = (string) get(route('de-ch.ai.llm.analytics.index').'?year=2026&month=3')->assertOk()->getContent();

    expect($html)->toContain('<link rel="canonical" href="'.route('de-ch.ai.llm.analytics.index').'">');
})->group('seo');

it('gives each locale its own title and description', function () {
    $de = (string) get(route('de-ch.contact.index'))->assertOk()->getContent();
    $en = (string) get(route('en-ch.contact.index'))->assertOk()->getContent();

    expect(titleOf($de))->not->toBe(titleOf($en));
})->group('seo');

it('marks error pages noindex', function () {
    // Error pages render without a PageDTO. Without an explicit robots tag they
    // would be indexable and compete with real content.
    $html = (string) get('/a-page-that-does-not-exist')->assertNotFound()->getContent();

    expect($html)->toContain('<meta name="robots" content="noindex,follow">');
})->group('seo');

it('keeps the network request page out of the index', function () {
    $html = (string) get(route('de-ch.network.request.index'))->assertOk()->getContent();

    expect($html)->toContain('<meta name="robots" content="noindex,nofollow">');
})->group('seo');

it('gives a news article its own title rather than the index title', function () {
    $news = News::factory()->create();

    $article = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();
    $index = (string) get(route('de-ch.news.index'))->assertOk()->getContent();

    expect(titleOf($article))->not->toBe(titleOf($index));
})->group('seo');

it('gives a network partner page its own title and self-referencing hreflang', function () {
    // The controller used to build this page's metadata from the network index,
    // so every partner page shared the index's title and description and its
    // hreflang alternates pointed back at /netzwerk.
    Network::factory()->create([
        'key' => 'baselhack',
        'name' => ['de_CH' => 'BaselHack', 'en_CH' => 'BaselHack'],
        'page_slug' => 'baselhack',
    ]);

    $detail = (string) get(route('de-ch.network.show', ['slug' => 'baselhack']))
        ->assertOk()->getContent();

    expect(titleOf($detail))->toBe('BaselHack')
        ->and(titleOf($detail))->not->toBe(titleOf((string) get(route('de-ch.network.index'))->getContent()));

    expect($detail)
        ->toContain('hreflang="de-CH" href="'.route('de-ch.network.show', ['slug' => 'baselhack']).'"')
        ->toContain('hreflang="en-CH" href="'.route('en-ch.network.show', ['slug' => 'baselhack']).'"');
})->group('seo');

it('links both language versions with crawlable anchors', function () {
    // A POST form would leave hreflang as the only connection between locales.
    $html = (string) get(route('de-ch.services.index'))->assertOk()->getContent();

    expect($html)->toContain('href="'.route('en-ch.services.index').'"');
})->group('seo');
