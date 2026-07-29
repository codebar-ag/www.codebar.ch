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

it('walks the chain without gaps, repeats or wrap-around', function () {
    $pages = PageNavigation::pages();

    foreach ($pages as $index => $page) {
        $next = PageNavigation::next($page['route']);

        if ($index === count($pages) - 1) {
            // The last page must not loop back to the start.
            expect($next)->toBeNull();

            continue;
        }

        expect($next)->not->toBeNull();

        $route = $next === null ? '' : $next['route'];

        expect($route)->toBe($pages[$index + 1]['route']);
        expect($route)->not->toBe($page['route']);
    }
})->group('unit', 'navigation');

it('returns nothing for a page outside the chain', function () {
    expect(PageNavigation::next('legal.imprint.index'))->toBeNull();
    expect(PageNavigation::next('news.show'))->toBeNull();
})->group('unit', 'navigation');

it('gives every page a label and a translated teaser', function () {
    foreach (PageNavigation::pages() as $page) {
        expect($page['label'])->not->toBe('');
        expect($page['teaser'])->not->toBe('');
        // An untranslated key would surface as the key itself.
        expect($page['teaser'])->not->toStartWith('components.');
    }
})->group('unit', 'navigation');
