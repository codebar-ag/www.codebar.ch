<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

function introMarkup(string $locale = LocaleEnum::DE->value): string
{
    $response = get(route(Str::slug($locale).'.start.index'));
    $response->assertOk();

    return (string) $response->getContent();
}

/**
 * @return array<int, string>
 */
function introItems(): array
{
    $items = __('components.intro.what_we_do.items');

    return is_array($items) ? array_values(array_filter($items, is_string(...))) : [];
}

function introPanel(string $html, int $index): string
{
    $panel = Str::of($html)->after('data-panel="'.$index.'"');

    return $panel->contains('data-panel="'.($index + 1).'"')
        ? $panel->before('data-panel="'.($index + 1).'"')->toString()
        : $panel->before('</fieldset>')->toString();
}

it('renders the three sections as panels at once', function () {
    $html = introMarkup();

    foreach (range(0, 2) as $index) {
        expect($html)->toContain('data-panel="'.$index.'"');
    }

    expect($html)->not->toContain('data-panel="3"');
});

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

    foreach (introItems() as $item) {
        expect($html)->toContain($item);
    }
});

it('keeps the four areas on one line each', function () {
    $panel = introPanel(introMarkup(), 1);

    foreach (introItems() as $item) {
        expect($panel)->toContain('<span class="[&_b]:font-normal [&_b]:text-brand">'.$item.'</span>');
    }
});

it('switches without javascript through radio inputs', function () {
    $html = introMarkup();

    foreach (range(0, 2) as $index) {
        expect($html)
            ->toContain('id="intro-tab-'.$index.'"')
            ->toContain('data-tab="'.$index.'"')
            ->toContain('for="intro-tab-'.$index.'"');
    }

    expect($html)->toContain('name="intro-tab"');
});

it('puts every section into the tab bar, and nothing else', function () {
    $bar = Str::of(introMarkup())->after('</legend>')->before('intro-tabs__panels')->toString();

    expect(substr_count($bar, '<label'))->toBe(3)
        ->and($bar)->toContain('for="intro-tab-0"');
});

it('opens on the first section instead of an overview', function () {
    $html = introMarkup();

    $first = Str::of($html)->after('id="intro-tab-0"')->before('/>')->toString();
    $second = Str::of($html)->after('id="intro-tab-1"')->before('/>')->toString();

    expect($first)->toContain('checked')
        ->and($second)->not->toContain('checked')
        ->and(introPanel($html, 0))->toContain(__('components.intro.who_we_are.text'));
});

it('no longer lists the sections as teasers anywhere', function () {
    $window = Str::of(introMarkup())->after('<fieldset')->before('</fieldset>')->toString();

    foreach (['who_we_are', 'what_we_do', 'how_we_work'] as $key) {
        expect($window)->not->toContain(__('components.intro.'.$key.'.teaser'));
    }
});

it('exposes one number key per tab', function () {
    $html = introMarkup();

    foreach (range(1, 3) as $shortcut) {
        expect($html)->toContain('data-shortcut="'.$shortcut.'"');
    }

    expect($html)->not->toContain('data-shortcut="4"')
        ->and($html)->toContain('x-data="introTabs"');
});

it('names the shortcuts for screen readers', function () {
    expect(introMarkup())->toContain(__('components.intro.shortcuts'));
});

it('renders the arrow hints as real, labelled, clickable buttons', function () {
    $html = introMarkup();

    expect($html)
        ->toContain('<button type="button" @click="step(-1)" aria-label="'.__('components.intro.prev_section').'"')
        ->toContain('<button type="button" @click="step(1)" aria-label="'.__('components.intro.next_section').'"');
});

it('offers the next section below the first two panels', function () {
    $html = introMarkup();

    expect(introPanel($html, 0))->toContain('for="intro-tab-1"')
        ->and(introPanel($html, 1))->toContain('for="intro-tab-2"');
});

it('ends the last panel on the contact page instead of another tab', function () {
    $panel = introPanel(introMarkup(), 2);

    expect($panel)
        ->toContain(localized_route('contact.index'))
        ->toContain(__('components.intro.cta'));
});

it('never renders a contact form', function () {
    expect(introMarkup())->not->toContain('<form');
});

it('keeps the page heading above the window, exactly once', function () {
    $html = introMarkup();

    expect(substr_count($html, '<h1'))->toBe(1)
        ->and(introPanel($html, 0))->not->toContain('<h1')
        ->and(Str::before($html, '<fieldset'))->toContain('<h1');
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

it('spends no vertical space on prompt lines', function () {
    expect(introMarkup())->not->toContain('➜');
});

it('lets the panel height follow the content on mobile and freezes it on desktop', function () {
    $css = file_get_contents(resource_path('css/app.css'));

    if ($css === false) {
        throw new RuntimeException('Unable to read resources/css/app.css');
    }

    $intro = Str::after($css, '.intro-tabs__panels');

    $mobile = Str::before($intro, '@media');
    $desktop = Str::after($intro, '@media (width >= 40rem)');

    expect($mobile)->toContain('display: none')
        ->and($mobile)->not->toContain('display: grid')
        ->and($desktop)->toContain('display: grid')
        ->and($desktop)->toContain('grid-area: 1 / 1');

    expect(Str::before($intro, '.intro-tabs__panel:not(:first-child)'))
        ->toContain('@supports not selector(:has(*))');
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
