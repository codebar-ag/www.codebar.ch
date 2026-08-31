<?php

declare(strict_types=1);

use App\Enums\ApplicationStatusEnum;
use App\Enums\JobPositionStatusEnum;
use App\Enums\LocaleEnum;
use App\Jobs\Applications\SendApplicationLinkJob;
use App\Models\Application;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Crypt;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
    ]);
});

it('creates a draft for an unknown email and dispatches the link job', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('de-ch.jobs.internship.show'));

    $application = Application::query()->sole();

    expect($application->email)->toBe('mina@example.com')
        ->and($application->job_key)->toBe(Application::JOB_KEY_INTERNSHIP)
        ->and($application->jobPosition?->key)->toBe(Application::JOB_KEY_INTERNSHIP)
        ->and($application->status)->toBe(ApplicationStatusEnum::Draft);

    Bus::assertDispatched(SendApplicationLinkJob::class, function (SendApplicationLinkJob $job) {
        return $job->email === 'mina@example.com'
            && $job->jobKey === Application::JOB_KEY_INTERNSHIP
            && $job->locale === LocaleEnum::DE->value;
    });
})->group('applications');

it('dispatches the link job with the english locale on the english site', function () {
    Bus::fake();

    post(route('en-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('en-ch.jobs.internship.show'));

    Bus::assertDispatched(SendApplicationLinkJob::class, function (SendApplicationLinkJob $job) {
        return $job->locale === LocaleEnum::EN->value;
    });
})->group('applications');

it('shows the entered email address in the confirmation', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertSessionHas('status', __('We sent a link to :email. Please also check your spam folder.', ['email' => '<strong>mina@example.com</strong>']));
})->group('applications');

it('reuses the existing application instead of creating a duplicate', function () {
    Bus::fake();

    $application = Application::factory()->create(['email' => 'mina@example.com']);

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('de-ch.jobs.internship.show'));

    assertDatabaseCount('applications', 1);

    expect(Application::query()->sole()->id)->toBe($application->id);
})->group('applications');

it('normalizes the email address to lowercase', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'Mina@Example.com']);

    expect(Application::query()->sole()->email)->toBe('mina@example.com');
})->group('applications');

it('responds identically for known and unknown addresses', function () {
    Bus::fake();

    $known = post(route('de-ch.jobs.internship.request.store'), ['email' => 'known@example.com']);
    $unknown = post(route('de-ch.jobs.internship.request.store'), ['email' => 'unknown@example.com']);

    expect($known->getStatusCode())->toBe($unknown->getStatusCode());
})->group('applications');

it('validates the email address', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'not-an-email'])
        ->assertSessionHasErrors('email');

    Bus::assertNotDispatched(SendApplicationLinkJob::class);
})->group('applications');

it('throttles repeated requests', function () {
    Bus::fake();

    foreach (range(1, 5) as $i) {
        post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
            ->assertRedirect();
    }

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertStatus(429);
})->group('applications');

it('redirects a submission faster than the honeypot minimum back to the form', function () {
    Bus::fake();

    $validFrom = Crypt::encrypt(now()->addSeconds(2)->getTimestamp());

    post(route('de-ch.jobs.internship.request.store'), [
        'email' => 'mina@example.com',
        config()->string('honeypot.name_field_name') => '',
        config()->string('honeypot.valid_from_field_name') => $validFrom,
    ])
        ->assertRedirect();

    Bus::assertNotDispatched(SendApplicationLinkJob::class);
    assertDatabaseCount('applications', 0);
})->group('applications');

it('accepts a submission once the honeypot minimum has passed', function () {
    Bus::fake();

    $validFrom = Crypt::encrypt(now()->subSecond()->getTimestamp());

    post(route('de-ch.jobs.internship.request.store'), [
        'email' => 'mina@example.com',
        config()->string('honeypot.name_field_name') => '',
        config()->string('honeypot.valid_from_field_name') => $validFrom,
    ])
        ->assertRedirect(route('de-ch.jobs.internship.show'));

    Bus::assertDispatched(SendApplicationLinkJob::class);
})->group('applications');

it('keeps the entered email when a too fast submission is redirected back', function () {
    Bus::fake();

    $validFrom = Crypt::encrypt(now()->addSeconds(2)->getTimestamp());

    post(route('de-ch.jobs.internship.request.store'), [
        'email' => 'mina@example.com',
        config()->string('honeypot.name_field_name') => '',
        config()->string('honeypot.valid_from_field_name') => $validFrom,
    ])
        ->assertRedirect()
        ->assertSessionHasInput('email', 'mina@example.com');
})->group('applications');

it('shows the confirmation as a toast on the internship page', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('de-ch.jobs.internship.show'));

    get(route('de-ch.jobs.internship.show'))
        ->assertSee('x-data="toast"', false)
        ->assertSee('<strong>mina@example.com</strong>', false);
})->group('applications');

it('drops a submission with the honeypot field filled', function () {
    Bus::fake();

    post(route('de-ch.jobs.internship.request.store'), [
        'email' => 'bot@example.com',
        config()->string('honeypot.name_field_name') => 'i am a bot',
    ]);

    Bus::assertNotDispatched(SendApplicationLinkJob::class);
    assertDatabaseCount('applications', 0);
})->group('applications');

it('rejects new application requests while the position is in process', function () {
    Bus::fake();

    JobPosition::query()->update(['status' => JobPositionStatusEnum::InProcess]);

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('de-ch.jobs.internship.show'))
        ->assertSessionHas('status', __('Internship closed teaser'));

    Bus::assertNotDispatched(SendApplicationLinkJob::class);
    assertDatabaseCount('applications', 0);
})->group('applications');
