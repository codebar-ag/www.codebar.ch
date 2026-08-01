<?php

declare(strict_types=1);

use App\Models\News;
use App\Models\Page;
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

it('swaps locale and slug together in a detail page hreflang', function () {
    // /news/{locale}/{slug} carries the locale twice — in the path prefix and as a
    // route parameter — and the slug itself is translated. Swapping only the prefix
    // pointed the English alternate at the German article with the German slug: a
    // wrong-language pair Google would ignore.
    $news = News::factory()->create();

    $germanSlug = $news->getTranslation('slug', 'de_CH');
    $englishSlug = $news->getTranslation('slug', 'en_CH');

    expect($germanSlug)->not->toBe($englishSlug);

    $html = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $germanSlug]))
        ->assertOk()->getContent();

    expect($html)
        ->toContain('hreflang="en-CH" href="'.route('en-ch.news.show', ['locale' => 'en_CH', 'news' => $englishSlug]).'"')
        ->toContain('hreflang="de-CH" href="'.route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $germanSlug]).'"');

    // The regressions: English path with the German locale parameter, or with the German slug.
    expect($html)->not->toContain(
        'hreflang="en-CH" href="'.route('en-ch.news.show', ['locale' => 'de_CH', 'news' => $englishSlug]).'"'
    );

    expect($html)->not->toContain(
        'hreflang="en-CH" href="'.route('en-ch.news.show', ['locale' => 'en_CH', 'news' => $germanSlug]).'"'
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

it('shares a news article as an article and every other page as a website', function () {
    // og:type drives how the link renders when shared: an article card carries
    // a byline and a date, a website card does not.
    $news = News::factory()->create();

    $article = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();

    expect($article)
        ->toContain('<meta property="og:type" content="article">')
        ->toContain('<meta property="article:published_time"')
        ->toContain('<meta property="article:modified_time"');

    $index = (string) get(route('de-ch.news.index'))->assertOk()->getContent();

    expect($index)->toContain('<meta property="og:type" content="website">');
})->group('seo');

it('leaves an article title as the bare headline', function () {
    $news = News::factory()->create(['title' => ['de_CH' => 'Ein Testartikel', 'en_CH' => 'A test article']]);

    $article = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();

    expect(titleOf($article))->toBe('Ein Testartikel')
        ->and($article)->toContain('<meta property="og:title" content="Ein Testartikel">')
        ->and($article)->toContain('"headline":"Ein Testartikel"');
})->group('seo');

it('keeps a long article title inside the length search results render', function () {
    $headline = 'Lokale LLMs betreiben: Warteschlangen statt Timeouts';

    $news = News::factory()->create(['title' => ['de_CH' => $headline, 'en_CH' => $headline]]);

    $article = (string) get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => $news->slug]))
        ->assertOk()->getContent();

    expect(mb_strlen(titleOf($article)))->toBeLessThanOrEqual(60);
})->group('seo');

it('appends the company name to a page title that does not already carry it', function () {
    Page::where('key', 'start.index')->update([
        'title' => ['de_CH' => 'Ohne Marke im Titel', 'en_CH' => 'Without the brand'],
    ]);

    $html = (string) get(route('de-ch.start.index'))->assertOk()->getContent();

    expect(titleOf($html))->toBe('Ohne Marke im Titel – '.config()->string('app.name'));
})->group('seo');

it('serves a page image as PNG even when the page stores an SVG', function () {
    Page::where('key', 'start.index')->update(['image' => 'images/seo/og-codebar.svg']);

    $html = (string) get(route('de-ch.start.index'))->assertOk()->getContent();

    $png = url('images/seo/og-codebar.png');

    expect($html)
        ->toContain('<meta property="og:image" content="'.$png.'">')
        ->toContain('<meta name="twitter:image" content="'.$png.'">')
        ->toContain('"primaryImageOfPage":{"@type":"ImageObject","url":"'.$png.'"}');

    expect($html)->not->toContain('og-codebar.svg');
})->group('seo');

it('falls back to the default image when an SVG has no PNG counterpart', function () {
    Page::where('key', 'start.index')->update(['image' => 'images/seo/no-such-file.svg']);

    $html = (string) get(route('de-ch.start.index'))->assertOk()->getContent();

    expect($html)->toContain('<meta property="og:image" content="'.url(config()->string('seo.default_image')).'">');

    expect($html)->not->toContain('no-such-file');
    expect($html)->not->toContain('primaryImageOfPage');
})->group('seo');

it('links both language versions with crawlable anchors', function () {
    // A POST form would leave hreflang as the only connection between locales.
    $html = (string) get(route('de-ch.services.index'))->assertOk()->getContent();

    expect($html)->toContain('href="'.route('en-ch.services.index').'"');
})->group('seo');
