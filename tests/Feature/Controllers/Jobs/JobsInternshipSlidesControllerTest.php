<?php

declare(strict_types=1);

use App\Models\Contact;

use function Pest\Laravel\get;

it('renders the kiosk deck with its dedicated asset bundle', function () {
    get(route('de-ch.jobs.internship.slides'))
        ->assertOk()
        ->assertSee('kiosk', false)
        ->assertDontSee('resources/js/app.js', false)
        ->assertSee('Wir erwecken Ideen')
        ->assertSee('Der ganze Weg.')
        ->assertSee('Praktikum')
        ->assertSee('images/qr/praktikum.svg')
        ->assertSee('codebar.ch/stellen/praktikum');
})->group('applications');

it('keeps the kiosk deck out of search engines', function () {
    get(route('de-ch.jobs.internship.slides'))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, nofollow"/>', false);
})->group('applications');

it('shows the mentors slide only when the contacts are imported', function () {
    get(route('de-ch.jobs.internship.slides'))
        ->assertOk()
        ->assertDontSee('Fragen zum Praktikum');

    $section = ['employees' => ['role' => ['de_CH' => 'Applikationsentwickler', 'en_CH' => 'Application Developer']]];

    Contact::factory()->create([
        'key' => 'tobias-brogle',
        'name' => 'Tobias Brogle',
        'sections' => $section,
        'icons' => ['email' => 'tobias.brogle@codebar.ch'],
    ]);
    Contact::factory()->create([
        'key' => 'julian-leipert',
        'name' => 'Julian Leipert',
        'sections' => $section,
        'icons' => ['email' => 'julian.leipert@codebar.ch'],
    ]);

    get(route('de-ch.jobs.internship.slides'))
        ->assertOk()
        ->assertSee('Tobias Brogle')
        ->assertSee('Julian Leipert')
        ->assertSee('tobias.brogle@codebar.ch');
})->group('applications');
