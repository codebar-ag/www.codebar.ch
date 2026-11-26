<?php

declare(strict_types=1);

use App\Models\InfrastructureStack;
use Database\Seeders\InfrastructureSeeder;

use function Pest\Laravel\seed;

const INFRA_OVERLAY_OPEN = '() => document.querySelector("#infraOverlay").open';
const INFRA_DIRECT = '() => { history.replaceState(null, "", "#grafik"); location.reload() }';
const INFRA_DIALOG_OPEN = '() => document.querySelector("#infraDialog").open';
const INFRA_POSITION = '() => { const s = document.querySelector("#infraScroller"), t = document.querySelector("#sxScroll"), g = document.querySelector("#sxStage"); return Math.round(s.scrollTop / (t.offsetHeight - g.offsetHeight) * (document.querySelectorAll("#sxWorld .sx-plate[data-k]").length + 1) * 100) / 100 }';

beforeEach(function () {
    seed(InfrastructureSeeder::class);
});

function infraScrollTo(float $position): string
{
    return '() => { const s = document.querySelector("#infraScroller"), t = document.querySelector("#sxScroll"), g = document.querySelector("#sxStage"); s.scrollTo({ top: '.$position.' / (document.querySelectorAll("#sxWorld .sx-plate[data-k]").length + 1) * (t.offsetHeight - g.offsetHeight), behavior: "instant" }) }';
}

function infraCustomer(): InfrastructureStack
{
    return InfrastructureStack::factory()->create([
        'slug' => 'web.example',
        'domain' => 'www.example.ch',
        'sketch' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 900"><rect width="1440" height="900" fill="#eee"/></svg>',
        'services' => ['domain', 'dns', 'app', 'db', 'spaces'],
        'overrides' => ['spaces' => ['provider' => 'Cloudflare R2', 'countries' => ['WW']]],
    ]);
}

it('opens the graphic from the button and closes the windows in order with Escape', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse();

    $page->click('[data-infra-open]')->wait(0.6);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue()
        ->and($page->script('() => document.querySelectorAll("#sxWorld .sx-plate[data-k]").length'))->toBe(7)
        ->and($page->script('() => document.querySelectorAll("#sxWorld .sx-card").length'))->toBe(24);

    $page->script(infraScrollTo(6.95));
    $page->wait(1.5);
    $page->script('() => document.querySelector(".sx-card[data-station=\"domain\"]").click()');
    $page->wait(0.3);

    expect($page->script(INFRA_DIALOG_OPEN))->toBeTrue();

    $page->assertSeeIn('#infraDialog h2', 'Domain')
        ->assertSeeIn('#infraDialog .infra-dialog-lead', 'Hosttech')
        ->assertAttribute('#infraDialog a.infra-dialog-open', 'target', '_blank')
        ->assertAttributeContains('#infraDialog a.infra-dialog-open', 'href', '/infrastruktur/domain')
        ->assertAttribute('#infraDialog ul a', 'target', '_blank');

    $page->keys('#infraDialog', 'Escape')->wait(0.3);

    expect($page->script(INFRA_DIALOG_OPEN))->toBeFalse()
        ->and($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->keys('#infraOverlay', 'Escape')->wait(0.3);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse();

    $page->assertNoJavaScriptErrors();
})->group('infrastructure');

it('closes the detail window with its button and with a click beside it', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);
    $open = '() => document.querySelector(".sx-card[data-station=\"app\"]").click()';

    $page->script(infraScrollTo(6.95));
    $page->wait(1.2);
    $page->script($open);
    $page->wait(0.3);
    $page->click('#infraDialog [data-close]')->wait(0.3);

    expect($page->script(INFRA_DIALOG_OPEN))->toBeFalse();

    $page->script($open);
    $page->wait(0.3);
    $page->script('() => document.querySelector("#infraDialog").dispatchEvent(new MouseEvent("click", { bubbles: true }))');
    $page->wait(0.3);

    expect($page->script(INFRA_DIALOG_OPEN))->toBeFalse()
        ->and($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->assertNoJavaScriptErrors();
})->group('infrastructure');

it('opens the graphic from the direct address and removes the mark when it is closed', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->click('[data-infra-close]')->wait(0.3);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse()
        ->and($page->script('() => location.hash'))->toBe('');

    $page->script('() => { location.hash = "grafik" }');
    $page->wait(0.6);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->assertNoJavaScriptErrors();
})->group('infrastructure');

it('keeps the page behind the graphic still and snaps to the nearest layer', function (float $from, float $to) {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);

    $page->script(infraScrollTo($from));
    $page->wait(1.6);

    expect($page->script(INFRA_POSITION))->toEqualWithDelta($to, 0.03)
        ->and($page->script('() => Math.round(scrollY)'))->toBe(0);

    $page->assertNoJavaScriptErrors();
})->with([
    'small scroll returns to the start' => [0.3, 0.0],
    'just before the first layer' => [0.8, 0.95],
    'between two layers, nearer the earlier' => [2.2, 1.95],
    'between two layers, nearer the later' => [4.6, 4.95],
    'near the end goes to the overview' => [7.6, 8.0],
    'the end stays' => [8.0, 8.0],
])->group('infrastructure');

it('starts with a click on the cover, jumps with the bar and returns with the reset button', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);

    $page->script('() => document.querySelector(".sx-site").click()');
    $page->wait(2.8);

    expect($page->script(INFRA_POSITION))->toEqualWithDelta(0.95, 0.06);

    $page->script('() => document.querySelector(".sx-time [data-t=\"3\"]").click()');
    $page->wait(2.0);

    expect($page->script(INFRA_POSITION))->toEqualWithDelta(3.95, 0.05);

    $page->script('() => document.querySelector(".sx-time [data-t=\"-1\"]").click()');
    $page->wait(2.2);

    expect($page->script(INFRA_POSITION))->toEqualWithDelta(0.0, 0.03);

    $page->assertNoJavaScriptErrors();
})->group('infrastructure');

it('opens a customer view by itself and keeps the customer in every link', function () {
    infraCustomer();

    $page = visit('/infrastruktur?site=web.example')->resize(1440, 900)->wait(0.6);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue()
        ->and($page->script('() => document.querySelectorAll("#sxWorld .sx-plate[data-k]").length'))->toBe(3)
        ->and($page->script('() => document.querySelector(".sx-browser-bar span").textContent'))->toBe('https://www.example.ch')
        ->and($page->script('() => document.querySelector(".sx-browser img").src.startsWith("data:image/svg+xml;base64,")'))->toBeTrue();

    $page->script(infraScrollTo(3.95));
    $page->wait(1.2);
    $page->script('() => document.querySelector(".sx-card[data-station=\"spaces\"]").click()');
    $page->wait(0.3);

    $page->assertSeeIn('#infraDialog .infra-dialog-lead', 'Cloudflare R2')
        ->assertAttributeContains('#infraDialog a.infra-dialog-open', 'href', '/infrastruktur/spaces?site=web.example');

    $page->keys('#infraDialog', 'Escape')->wait(0.2);
    $page->click('[data-infra-close]')->wait(0.3);
    $page->click('main ul a[href*="/infrastruktur/spaces?site=web.example"]')
        ->assertPathIs('/infrastruktur/spaces')
        ->assertQueryStringHas('site', 'web.example')
        ->assertSee('Cloudflare R2')
        ->assertNoJavaScriptErrors();
})->group('infrastructure');

it('shows only the list on a phone', function (string $path) {
    $page = visit($path)->on()->mobile()->wait(0.6);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse()
        ->and($page->script('() => getComputedStyle(document.querySelector("[data-infra-open]")).display'))->toBe('none')
        ->and($page->script('() => document.documentElement.scrollWidth <= innerWidth'))->toBeTrue();

    $page->assertSee('Hosttech')->assertNoJavaScriptErrors();
})->with(['/infrastruktur', '/infrastruktur#grafik'])->group('infrastructure');

it('closes the graphic when the window becomes narrow and opens again when it is wide', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->resize(700, 900)->wait(0.5);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse();

    $page->resize(979, 900)->wait(0.3);
    $page->script('() => document.querySelector("[data-infra-open]").click()');
    $page->wait(0.3);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeFalse();

    $page->resize(980, 900)->wait(0.3);
    $page->click('[data-infra-open]')->wait(0.5);

    expect($page->script(INFRA_OVERLAY_OPEN))->toBeTrue();

    $page->assertNoJavaScriptErrors();
})->group('infrastructure');

it('filters the list by one or several areas and keeps the choice in the address', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $visible = '() => [...document.querySelectorAll("[data-infra-layer]")].filter(s => s.offsetParent !== null).map(s => s.dataset.infraLayer).join(",")';

    expect($page->script($visible))->toBe('network,platform,services,ai,monitoring,development,backups');

    $page->click('[data-infra-filter="monitoring"]')->click('[data-infra-filter="network"]');

    expect($page->script($visible))->toBe('network,monitoring');

    $page->assertQueryStringHas('bereich', 'network,monitoring')
        ->assertAttribute('[data-infra-filter="network"]', 'aria-pressed', 'true')
        ->assertAttribute('[data-infra-filter-reset]', 'aria-pressed', 'false')
        ->assertDontSee('Laravel Cloud')
        ->assertSee('Oh Dear');

    $page->click('[data-infra-filter-reset]');

    expect($page->script($visible))->toBe('network,platform,services,ai,monitoring,development,backups');

    $page->assertQueryStringMissing('bereich')->assertNoJavaScriptErrors();
})->group('infrastructure');

it('opens the list with the areas from a shared address', function () {
    $page = visit('/infrastruktur?bereich=backups,nothing');

    expect($page->script('() => [...document.querySelectorAll("[data-infra-layer]")].filter(s => s.offsetParent !== null).map(s => s.dataset.infraLayer).join(",")'))->toBe('backups');

    $page->assertAttribute('[data-infra-filter="backups"]', 'aria-pressed', 'true')->assertNoJavaScriptErrors();
})->group('infrastructure');

it('leads from the list to a detail page with links in a new tab', function () {
    visit('/infrastruktur')
        ->click('a[href$="/infrastruktur/ohdear"]')
        ->assertPathIs('/infrastruktur/ohdear')
        ->assertSeeIn('h1:visible', 'Uptime')
        ->assertSee('Oh Dear')
        ->assertAttribute('main li:first-child a[href^="https://ohdear.app"]', 'target', '_blank')
        ->assertNoJavaScriptErrors();
})->group('infrastructure');

it('renders the pages in both languages without JavaScript errors or console output', function (string $path, string $heading) {
    visit($path)
        ->assertSeeIn('h1:visible', $heading)
        ->assertNoJavaScriptErrors()
        ->assertNoConsoleLogs();
})->with([
    'list de' => ['/infrastruktur', 'Infrastruktur und Dienste'],
    'list en' => ['/infrastructure', 'Infrastructure and services'],
    'detail de' => ['/infrastruktur/vimeo', 'Videos'],
    'detail en' => ['/infrastructure/vimeo', 'Video'],
])->group('infrastructure');

it('shows the error page for an unknown service', function () {
    visit('/infrastruktur/nothing')->assertSee(__('errors.title_client', [], 'de_CH'));
})->group('infrastructure');

it('shows https in the address bar and opens the certificate without starting the tour', function () {
    $page = visit('/infrastruktur')->resize(1440, 900);
    $page->script(INFRA_DIRECT);
    $page->wait(1.2);

    expect($page->script('() => document.querySelector(".sx-browser-bar span").textContent'))->toBe('https://www.codebar.ch');

    $page->script('() => document.querySelector("button.sx-https").click()');
    $page->wait(0.4);

    expect($page->script('() => document.getElementById("infraDialog").open'))->toBeTrue()
        ->and($page->script('() => document.getElementById("infraDialogTitle").textContent'))->toBe('Zertifikat')
        ->and($page->script('() => document.querySelector("#infraDialog .infra-dialog-lead").textContent'))->toContain('Google Trust Services')
        ->and($page->script('() => document.querySelector("#infraDialog .infra-dialog-open").getAttribute("href")'))->toContain('/infrastruktur/tls')
        ->and($page->script(INFRA_POSITION))->toEqualWithDelta(0, 0.05);

    $page->assertNoJavaScriptErrors();
});
