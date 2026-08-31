<?php

declare(strict_types=1);

use App\Enums\JobPositionStatusEnum;
use App\Models\Application;
use App\Models\Contact;
use App\Models\JobPosition;

use function Pest\Laravel\get;

beforeEach(function () {
    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
        'route_name' => 'jobs.internship.show',
        'title' => ['de_CH' => 'IMS-Praktikum 2027/28', 'en_CH' => 'IMS Internship 2027/28'],
        'teaser' => ['de_CH' => 'Der ganze Weg der Softwareentwicklung.', 'en_CH' => 'The whole journey of software development.'],
    ]);
});

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

it('lists the internship as an open position on the jobs page', function () {
    get(route('de-ch.jobs.index'))
        ->assertOk()
        ->assertSee(route('de-ch.jobs.internship.show'))
        ->assertSeeInOrder([__('Jobs open positions heading'), 'IMS-Praktikum 2027/28', __('Details and application')])
        ->assertDontSee('Initiativbewerbung')
        ->assertDontSee(__('Jobs no open positions'));
})->group('applications');

it('carries a job posting schema node', function () {
    runArtisan('pages:import')->assertSuccessful();

    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSee('"JobPosting"', false)
        ->assertSee('"employmentType":"INTERN"', false);
})->group('applications');

it('is listed in the sitemap', function () {
    runArtisan('pages:import')->assertSuccessful();

    get('/sitemap.xml')
        ->assertOk()
        ->assertSee(route('de-ch.jobs.internship.show'));
})->group('applications');

it('shows the closed notice instead of the application form while the position is in process', function () {
    JobPosition::query()->update(['status' => JobPositionStatusEnum::InProcess]);

    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertSee(__('Internship closed body'))
        ->assertDontSee(__('Internship apply body'));
})->group('applications');

it('moves an in-process position out of the open list and marks it with a badge', function () {
    JobPosition::query()->update(['status' => JobPositionStatusEnum::InProcess]);

    get(route('de-ch.jobs.index'))
        ->assertOk()
        ->assertSeeInOrder([__('Jobs training heading'), 'IMS-Praktikum 2027/28', __('Job status in process'), __('Job in process note'), __('Jobs open positions heading'), __('Jobs no open positions')])
        ->assertDontSee(route('de-ch.jobs.internship.show'));
})->group('applications');

it('shows the empty state when no positions exist at all', function () {
    JobPosition::query()->delete();

    get(route('de-ch.jobs.index'))
        ->assertOk()
        ->assertSee(__('Jobs no open positions'))
        ->assertDontSee('IMS-Praktikum 2027/28');
})->group('applications');

it('omits the job posting schema while the position is in process', function () {
    runArtisan('pages:import')->assertSuccessful();

    JobPosition::query()->update(['status' => JobPositionStatusEnum::InProcess]);

    get(route('de-ch.jobs.internship.show'))
        ->assertOk()
        ->assertDontSee('"JobPosting"', false);
})->group('applications');
