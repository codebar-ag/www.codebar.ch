<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('renders the fair qr page with both codes', function () {
    get(route('de-ch.jobs.internship.qr'))
        ->assertOk()
        ->assertSee('images/qr/praktikum.svg')
        ->assertSee('images/qr/praktikum-bewerben.svg')
        ->assertSee('codebar.ch/stellen/praktikum')
        ->assertSee('Praktikum');
})->group('applications');

it('keeps the qr page out of search engines', function () {
    get(route('de-ch.jobs.internship.qr'))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, nofollow"/>', false);
})->group('applications');

it('anchors the application form for the second qr code', function () {
    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSee('id="bewerbung"', false);
})->group('applications');
