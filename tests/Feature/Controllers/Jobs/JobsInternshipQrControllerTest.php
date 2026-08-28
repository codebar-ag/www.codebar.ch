<?php

declare(strict_types=1);

use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('renders the qr page with a single tab and the dynamic code', function () {
    get(route('de-ch.jobs.internship.qr'))
        ->assertOk()
        ->assertSee(route('de-ch.jobs.internship.qr.image'))
        ->assertSee(Str::after(route('de-ch.jobs.internship.show'), '://'))
        ->assertSee('praktikum')
        ->assertDontSee('bewerben');
})->group('applications');

it('keeps the qr page out of search engines', function () {
    get(route('de-ch.jobs.internship.qr'))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, nofollow"/>', false);
})->group('applications');

it('serves the qr code as svg encoding the internship page url', function () {
    $first = get(route('de-ch.jobs.internship.qr.image'))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/svg+xml');

    expect($first->getContent())
        ->toContain('<svg')
        ->toBe(get(route('de-ch.jobs.internship.qr.image'))->getContent());
})->group('applications');

it('anchors the application form for deep links', function () {
    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSee('id="bewerbung"', false);
})->group('applications');
