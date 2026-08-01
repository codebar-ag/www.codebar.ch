<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

function introMarkup(string $locale = LocaleEnum::DE->value): string
{
    $response = get(route(Str::slug($locale).'.start.index'));
    $response->assertOk();

    return $response->getContent();
}

function introPanel(string $html, int $index): string
{
    $panel = Str::of($html)->after('data-panel="'.$index.'"');

    return $panel->contains('data-panel="'.($index + 1).'"')
        ? $panel->before('data-panel="'.($index + 1).'"')->toString()
        : $panel->before('</fieldset>')->toString();
}

it('renders all four sections as panels at once', function () {
    $html = introMarkup();

    foreach (range(0, 3) as $index) {
        expect($html)->toContain('data-panel="'.$index.'"');
    }

    expect($html)->not->toContain('data-panel="4"');
});

/*
 * The regression this file exists for: the stylesheet lists one :has() rule per tab,
 * and a fourth tab was added while the stylesheet still stopped at the third — which
 * left that tab selectable but completely blank.
 */
it('has a stylesheet rule for every tab it renders', function () {
    $html = introMarkup();
    $css = file_get_contents(resource_path('css/app.css'));

    $tabs = preg_match_all('/data-tab="(\d+)"/', $html, $matches);
    expect($tabs)->toBeGreaterThan(0);

    foreach (array_unique($matches[1]) as $index) {
        expect($css)->toContain("[data-tab='".$index."']:checked) [data-panel='".$index."']")
            ->and($html)->toContain('data-panel="'.$index.'"');
    }
});

it('keeps every section readable without javascript', function () {
    $html = introMarkup();

    expect($html)
        ->toContain(__('components.intro.title'))
        ->toContain(__('components.intro.who_we_are.text'))
        ->toContain(__('components.intro.what_we_do.text'))
        ->toContain(__('components.intro.how_we_work.text'));

    foreach (__('components.intro.what_we_do.items') as $item) {
        expect($html)->toContain($item);
    }
});

it('switches without javascript through radio inputs', function () {
    $html = introMarkup();

    foreach (range(0, 3) as $index) {
        expect($html)
            ->toContain('id="intro-tab-'.$index.'"')
            ->toContain('data-tab="'.$index.'"')
            ->toContain('for="intro-tab-'.$index.'"');
    }

    expect($html)->toContain('name="intro-tab"');
});

it('opens on the start tab', function () {
    $html = introMarkup();

    $first = Str::of($html)->after('id="intro-tab-0"')->before('/>')->toString();
    $second = Str::of($html)->after('id="intro-tab-1"')->before('/>')->toString();

    expect($first)->toContain('checked')
        ->and($second)->not->toContain('checked');
});

it('exposes one number key per tab', function () {
    $html = introMarkup();

    foreach (range(1, 4) as $shortcut) {
        expect($html)->toContain('data-shortcut="'.$shortcut.'"');
    }

    expect($html)->toContain('x-data="introTabs"');
});

it('names the shortcuts for screen readers', function () {
    expect(introMarkup())->toContain(__('components.intro.shortcuts'));
});

it('opens the start tab with the page heading', function () {
    expect(introPanel(introMarkup(), 0))->toContain(__('components.intro.title'));
});

it('links from the start tab to each of the other three', function () {
    $panel = introPanel(introMarkup(), 0);

    foreach ([1, 2, 3] as $index) {
        expect($panel)->toContain('for="intro-tab-'.$index.'"');
    }

    foreach (['who_we_are', 'what_we_do', 'how_we_work'] as $key) {
        expect($panel)->toContain(__('components.intro.'.$key.'.teaser'));
    }
});

it('offers the next section below the two middle panels', function () {
    $html = introMarkup();

    expect(introPanel($html, 1))->toContain('for="intro-tab-2"')
        ->and(introPanel($html, 2))->toContain('for="intro-tab-3"');
});

it('ends the last panel on the contact page instead of another tab', function () {
    $panel = introPanel(introMarkup(), 3);

    expect($panel)
        ->toContain(localized_route('contact.index'))
        ->toContain(__('components.intro.cta'));
});

it('never renders a contact form', function () {
    expect(introMarkup())->not->toContain('<form');
});

it('keeps the page heading inside the window, exactly once', function () {
    $html = introMarkup();

    expect(substr_count($html, '<h1'))->toBe(1)
        ->and(introPanel($html, 0))->toContain('<h1');
});

it('gives the three content panels a heading of their own', function () {
    $html = introMarkup();

    foreach (['who_we_are', 'what_we_do', 'how_we_work'] as $key) {
        expect($html)->toContain('<h2 class="sr-only">'.__('components.intro.'.$key.'.title').'</h2>');
    }
});

it('titles the window with the full legal company name', function () {
    expect(introMarkup())->toContain(config('company.legal_name'));
});

it('puts the visitor, not the host, in front of the prompt', function () {
    expect(introMarkup(LocaleEnum::DE->value))->toContain('gast')
        ->and(introMarkup(LocaleEnum::EN->value))->toContain('visitor');
});

it('translates the commands per locale', function () {
    expect(introMarkup(LocaleEnum::DE->value))
        ->toContain('wer-wir-sind')
        ->toContain('was-wir-machen')
        ->toContain('wie-wir-arbeiten');

    expect(introMarkup(LocaleEnum::EN->value))
        ->toContain('who-we-are')
        ->toContain('what-we-do')
        ->toContain('how-we-work');
});
