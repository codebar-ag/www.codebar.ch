<?php

declare(strict_types=1);

use App\Models\Contact;

use function Pest\Laravel\get;

it('renders the internship page for both locales', function (string $routeName) {
    get(route($routeName))->assertOk();
})->with(['de-ch.jobs.internship.show', 'en-ch.jobs.internship.show'])->group('applications');

it('shows the journey, the focus honesty and the email entry at the bottom', function () {
    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Internship journey heading'),
            __('Internship phase plan heading'),
            __('Internship phase build heading'),
            __('Internship phase run heading'),
            __('Internship focus heading'),
            __('Internship apply heading'),
        ])
        ->assertSee(__('Start my application'));
})->group('applications');

it('shows the internship mentors and only them', function () {
    $section = ['employees' => ['role' => ['de_CH' => 'Applikationsentwickler', 'en_CH' => 'Application Developer']]];

    Contact::factory()->create([
        'key' => 'tobias-brogle',
        'name' => 'Tobias Brogle',
        'sections' => $section,
        'icons' => ['email' => 'tobias.brogle@codebar.ch', 'linkedin' => 'https://www.linkedin.com/in/tobias-brogle-21010b315'],
    ]);
    Contact::factory()->create([
        'key' => 'julian-leipert',
        'name' => 'Julian Leipert',
        'sections' => $section,
        'icons' => ['email' => 'julian.leipert@codebar.ch'],
    ]);
    Contact::factory()->create([
        'key' => 'someone-else',
        'name' => 'Andere Person',
        'sections' => $section,
    ]);

    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSee(__('Internship team heading'))
        ->assertSee('Tobias Brogle')
        ->assertSee('Julian Leipert')
        ->assertDontSee('Andere Person');
})->group('applications');

it('hides the mentor section when the contacts are not imported', function () {
    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertDontSee(__('Internship team heading'));
})->group('applications');

it('lists the internship as an open position on the jobs page below no other section than open positions', function () {
    get(route('de-ch.jobs.index'))
        ->assertOk()
        ->assertSee(__('Internship title'))
        ->assertSee(route('de-ch.jobs.internship.show'))
        ->assertSeeInOrder([__('Jobs open positions heading'), __('Internship title'), __('Jobs spontaneous heading')]);
})->group('applications');
