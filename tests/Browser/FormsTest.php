<?php

declare(strict_types=1);

use App\Enums\JobPositionStatusEnum;
use App\Models\Application;
use App\Models\JobPosition;
use App\Models\NetworkUser;
use App\Notifications\ApplicationLinkNotification;
use App\Notifications\NetworkManageLinkNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    // The honeypot rejects forms sent faster than a human types.
    config(['honeypot.valid_from_timestamp' => false]);
});

it('sends a network member the link to update their profile', function () {
    Notification::fake();
    $networkUser = NetworkUser::factory()->create(['email' => 'vincenzo@example.com']);

    visit('/netzwerk/request')
        ->type('email', 'vincenzo@example.com')
        ->click('main form button[type="submit"]')
        ->assertPathIs('/netzwerk/request')
        ->assertSee(__('If the email address is registered, we have sent you a link.', [], 'de_CH'))
        ->assertNoJavaScriptErrors();

    Notification::assertSentTo($networkUser, NetworkManageLinkNotification::class);
});

it('takes an applicant from the internship page to an autosaved application', function () {
    Notification::fake();

    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
        'route_name' => 'jobs.internship.show',
    ]);

    visit('/stellen/praktikum')
        ->type('input[name="email"]', 'mina@example.com')
        ->click('form[action$="/stellen/praktikum"] button[type="submit"]')
        ->assertPathIs('/stellen/praktikum')
        ->assertSee('mina@example.com')
        ->assertNoJavaScriptErrors();

    $application = Application::query()->sole();

    $link = '';
    Notification::assertSentTo($application, ApplicationLinkNotification::class, function (ApplicationLinkNotification $notification) use (&$link) {
        $link = $notification->url;

        return true;
    });

    visit($link)
        ->assertSee('mina@example.com')
        ->click('summary:has-text("'.__('Application block who heading', [], 'de_CH').'")')
        ->type('first_name', 'Mina')
        ->assertSee(__('Saved automatically at', [], 'de_CH'))
        ->assertNoJavaScriptErrors();

    expect($application->refresh()->first_name)->toBe('Mina');
});

it('previews the markdown of an application answer', function () {
    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
        'route_name' => 'jobs.internship.show',
    ]);
    $application = Application::query()->create(['job_key' => Application::JOB_KEY_INTERNSHIP, 'email' => 'mina@example.com']);

    visit(URL::temporarySignedRoute('de-ch.jobs.internship.application.show', now()->addHour(), ['application' => $application]))
        ->click('summary:has-text("'.__('Application block about heading', [], 'de_CH').'")')
        ->type('about', "Ich baue **Websites**\n\n- Laravel\n- Alpine")
        ->click('details[open] button:has-text("'.__('Preview', [], 'de_CH').'")')
        ->assertVisible('[role="dialog"] strong')
        ->assertSeeIn('[role="dialog"] ul', 'Laravel')
        ->assertNoJavaScriptErrors();
});
