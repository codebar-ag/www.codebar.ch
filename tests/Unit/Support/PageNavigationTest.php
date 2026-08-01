<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Support\PageNavigation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * This list drives two things at once — the "Discover more" grid on the start page and
 * the next-page card at the foot of every page. Team was in the header navigation but
 * missing here, so it appeared in neither. These tests lock that down.
 */
it('lists the main pages in reading order', function () {
    $routes = array_column(PageNavigation::pages(), 'route');

    expect($routes)->toBe([
        'start.index',
        'services.index',
        'about-us.index',
        'news.index',
        'ai.index',
        'network.index',
        'contact.index',
    ]);
})->group('unit', 'navigation');

it('covers every page linked in the header navigation', function () {
    // Read the actual navigation partial rather than a hand-kept list: a link added
    // there has to reach the reading chain too.
    $markup = file_get_contents(resource_path('views/layouts/_partials/_navigation_desktop.blade.php'));

    expect($markup)->toBeString();

    // Blade comments hold links that are deliberately switched off (Technologies).
    $active = preg_replace('/\{\{--.*?--\}\}/s', '', (string) $markup) ?? '';

    preg_match_all("/localized_route\('([a-z0-9.\-]+)'\)/", $active, $matches);

    $linked = array_unique($matches[1]);
    $known = array_column(PageNavigation::pages(), 'route');

    expect(array_values(array_diff($linked, $known)))->toBe([]);
})->group('unit', 'navigation');

it('resolves every route in both locales', function () {
    foreach (PageNavigation::pages() as $page) {
        foreach (LocaleEnum::cases() as $locale) {
            $name = Str::slug($locale->value).'.'.$page['route'];

            expect(Route::has($name))->toBeTrue("route {$name} is missing");
        }
    }
})->group('unit', 'navigation');

it('sends each page on to its curated next page', function (string $from, string $to) {
    $next = PageNavigation::next($from);

    expect($next)->not->toBeNull();
    expect($next === null ? '' : $next['route'])->toBe($to);
})->with([
    ['services.index', 'about-us.index'],
    ['about-us.index', 'contact.index'],
    ['news.index', 'ai.index'],
    ['news.show', 'services.index'],
    ['ai.index', 'services.index'],
    ['ai.llm.index', 'ai.index'],
    ['ai.llm.analytics.index', 'services.index'],
    ['network.index', 'contact.index'],
    ['jobs.index', 'about-us.index'],
    ['media.index', 'network.index'],
])->group('unit', 'navigation');

it('never sends a page to itself', function () {
    foreach (PageNavigation::chain() as $from => $to) {
        expect($to)->not->toBe($from);
    }
})->group('unit', 'navigation');

it('only ever points at a page that carries a card', function () {
    $known = array_column(PageNavigation::pages(), 'route');

    expect(array_values(array_diff(array_values(PageNavigation::chain()), $known)))->toBe([]);
})->group('unit', 'navigation');

it('returns nothing for a page that ends the chain', function () {
    // The start page shows the full grid instead, contact is where the chain lands.
    expect(PageNavigation::next('start.index'))->toBeNull();
    expect(PageNavigation::next('contact.index'))->toBeNull();
    expect(PageNavigation::next('legal.imprint.index'))->toBeNull();
})->group('unit', 'navigation');

it('gives every page a label and a translated teaser', function () {
    foreach (PageNavigation::pages() as $page) {
        expect($page['label'])->not->toBe('');
        expect($page['teaser'])->not->toBe('');
        // An untranslated key would surface as the key itself.
        expect($page['teaser'])->not->toStartWith('components.');
    }
})->group('unit', 'navigation');
