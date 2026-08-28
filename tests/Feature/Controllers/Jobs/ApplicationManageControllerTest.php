<?php

declare(strict_types=1);

use App\Enums\ApplicationStatusEnum;
use App\Models\Application;
use App\Models\ApplicationFile;
use App\Notifications\ApplicationReceivedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\delete;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\put;
use function Pest\Laravel\travel;

function signedApplicationUrl(Application $application): string
{
    return URL::temporarySignedRoute(
        'de-ch.jobs.internship.application.show',
        now()->addDays(7),
        ['application' => $application],
    );
}

/**
 * @return array<string, mixed>
 */
function validSubmission(): array
{
    return [
        'action' => 'submit',
        'first_name' => 'Mina',
        'last_name' => 'Keller',
        'age' => 16,
        'city' => 'Basel',
        'interests' => 'Web-Entwicklung und **Open Source**.',
        'focus_fit' => 'Laravel klingt spannend.',
        'about' => 'Ich spiele Handball und baue kleine Websites.',
    ];
}

it('rejects the application form without a valid signature', function () {
    $application = Application::factory()->create();

    get(route('de-ch.jobs.internship.application.show', ['application' => $application]))
        ->assertForbidden();
})->group('applications');

it('rejects an expired application link', function () {
    $application = Application::factory()->create();
    $url = signedApplicationUrl($application);

    travel(8)->days();

    get($url)->assertForbidden();
})->group('applications');

it('shows the form with the stored draft, required badges and separated sections', function () {
    $application = Application::factory()->create([
        'email' => 'mina@example.com',
        'first_name' => 'Mina',
        'interests' => 'Web und Games',
    ]);

    get(signedApplicationUrl($application))
        ->assertOk()
        ->assertSee('mina@example.com')
        ->assertSee('Mina')
        ->assertSee('Web und Games')
        ->assertSee(__('Internship person heading'))
        ->assertSee(__('Required'))
        ->assertSee('multiple', false)
        ->assertSeeInOrder([__('Application block links heading'), __('Application documents')]);
})->group('applications');

it('uploads documents through autosave', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    patch(signedApplicationUrl($application), [
        'documents' => [
            UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            UploadedFile::fake()->create('zeugnis.pdf', 100, 'application/pdf'),
        ],
    ])
        ->assertOk()
        ->assertJson(['uploaded' => 2]);

    expect($application->files()->count())->toBe(2);
})->group('applications');

it('saves an incomplete draft without validation errors', function () {
    $application = Application::factory()->empty()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'first_name' => 'Mina',
        'interests' => 'Nur das hier.',
        'linkedin' => 'https://www.linkedin.com/in/mina-keller',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $application->refresh();

    expect($application->first_name)->toBe('Mina')
        ->and($application->interests)->toBe('Nur das hier.')
        ->and($application->linkedin)->toBe('https://www.linkedin.com/in/mina-keller')
        ->and($application->status)->toBe(ApplicationStatusEnum::Draft);
})->group('applications');

it('never lets a signed link change email, job or status directly', function () {
    $application = Application::factory()->create(['email' => 'mina@example.com']);

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'email' => 'evil@example.com',
        'job_key' => 'other',
        'status' => 'submitted',
    ])->assertRedirect();

    $application->refresh();

    expect($application->email)->toBe('mina@example.com')
        ->and($application->job_key)->toBe(Application::JOB_KEY_INTERNSHIP)
        ->and($application->status)->toBe(ApplicationStatusEnum::Draft);
})->group('applications');

it('autosaves text fields and returns the save time as json', function () {
    $application = Application::factory()->empty()->create();

    patch(signedApplicationUrl($application), [
        'about' => 'Autosave-Text',
    ])
        ->assertOk()
        ->assertJsonStructure(['saved_at']);

    expect($application->refresh()->about)->toBe('Autosave-Text');
})->group('applications');

it('rejects an unsigned autosave', function () {
    $application = Application::factory()->create();

    patch(route('de-ch.jobs.internship.application.autosave', ['application' => $application]), [
        'about' => 'Autosave-Text',
    ])->assertForbidden();
})->group('applications');

it('requires the person and interest blocks when submitting', function () {
    $application = Application::factory()->empty()->create();

    put(signedApplicationUrl($application), ['action' => 'submit'])
        ->assertSessionHasErrors(['first_name', 'last_name', 'age', 'city', 'interests', 'focus_fit', 'about']);

    expect($application->refresh()->status)->toBe(ApplicationStatusEnum::Draft);
})->group('applications');

it('keeps optional fields optional on submit', function () {
    Notification::fake();

    $application = Application::factory()->empty()->create();

    put(signedApplicationUrl($application), validSubmission())
        ->assertSessionHasNoErrors();

    expect($application->refresh()->status)->toBe(ApplicationStatusEnum::Submitted);
})->group('applications');

it('submits the application and notifies both sides', function () {
    Notification::fake();

    $application = Application::factory()->empty()->create();

    put(signedApplicationUrl($application), validSubmission())
        ->assertRedirect();

    $application->refresh();

    expect($application->status)->toBe(ApplicationStatusEnum::Submitted)
        ->and($application->submitted_at)->not->toBeNull();

    Notification::assertSentTo(
        $application,
        ApplicationSubmittedNotification::class,
        fn (ApplicationSubmittedNotification $notification) => str_contains($notification->url, 'signature='),
    );

    Notification::assertSentOnDemand(
        ApplicationReceivedNotification::class,
        fn (ApplicationReceivedNotification $notification, array $channels, AnonymousNotifiable $notifiable) => $notifiable->routes['mail'] === config()->string('company.email')
            && $notification->application->is($application),
    );
})->group('applications');

it('shows a submitted application read-only without a form', function () {
    $application = Application::factory()->submitted()->create([
        'first_name' => 'Mina',
        'interests' => 'Web und **Open Source**',
    ]);

    get(signedApplicationUrl($application))
        ->assertOk()
        ->assertSee('Mina')
        ->assertSee('<strong>Open Source</strong>', false)
        ->assertDontSee('<form', false)
        ->assertDontSee(__('Submit application'))
        ->assertSee(__('Application submitted hint', ['date' => $application->submitted_at?->format('d.m.Y H:i')]));
})->group('applications');

it('refuses every change to a submitted application', function () {
    Notification::fake();
    Storage::fake('s3');

    $application = Application::factory()->submitted()->create(['first_name' => 'Mina']);
    $file = ApplicationFile::factory()->create(['application_id' => $application->id]);

    put(signedApplicationUrl($application), validSubmission() + ['first_name' => 'Hacked'])->assertForbidden();
    patch(signedApplicationUrl($application), ['first_name' => 'Hacked'])->assertForbidden();

    delete(URL::temporarySignedRoute(
        'de-ch.jobs.internship.application.files.destroy',
        now()->addDays(7),
        ['application' => $application, 'applicationFile' => $file],
    ))->assertForbidden();

    expect($application->refresh()->first_name)->toBe('Mina')
        ->and($application->files()->count())->toBe(1);

    Notification::assertNothingSent();
})->group('applications');

it('opens every form section collapsed until a section carries an error', function () {
    $application = Application::factory()->empty()->create();

    get(signedApplicationUrl($application))
        ->assertOk()
        ->assertSee('<details', false)
        ->assertDontSee(' open>', false);

    followingRedirects()
        ->put(signedApplicationUrl($application), ['action' => 'submit', 'age' => 9])
        ->assertOk()
        ->assertSee(' open>', false);
})->group('applications');

it('stores pdf uploads privately on s3', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'documents' => [UploadedFile::fake()->create('lebenslauf.pdf', 200, 'application/pdf')],
    ])->assertSessionHasNoErrors();

    $file = $application->files()->sole();

    expect($file->original_name)->toBe('lebenslauf.pdf')
        ->and($file->disk)->toBe('s3')
        ->and($file->mime)->toBe('application/pdf');

    Storage::disk('s3')->assertExists($file->path);
})->group('applications');

it('rejects uploads that are not pdf', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'documents' => [UploadedFile::fake()->create('bild.jpg', 200, 'image/jpeg')],
    ])->assertSessionHasErrors('documents.0');
})->group('applications');

it('rejects more than ten documents at once', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'documents' => array_map(
            fn (int $i) => UploadedFile::fake()->create("datei-{$i}.pdf", 10, 'application/pdf'),
            range(1, 11),
        ),
    ])->assertSessionHasErrors('documents');

    expect($application->files()->count())->toBe(0);
})->group('applications');

it('rejects an age outside the plausible range even on a draft save', function () {
    $application = Application::factory()->empty()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'age' => 9,
    ])->assertSessionHasErrors('age');
})->group('applications');

it('rejects uploads over 10 MB', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'documents' => [UploadedFile::fake()->create('riesig.pdf', 11_000, 'application/pdf')],
    ])->assertSessionHasErrors('documents.0');
})->group('applications');

it('deletes an uploaded document from disk and database', function () {
    Storage::fake('s3');
    Storage::disk('s3')->put('applications/documents/1-abc.pdf', 'pdf');

    $application = Application::factory()->create();
    $file = ApplicationFile::factory()->create([
        'application_id' => $application->id,
        'path' => 'applications/documents/1-abc.pdf',
    ]);

    $url = URL::temporarySignedRoute(
        'de-ch.jobs.internship.application.files.destroy',
        now()->addDays(7),
        ['application' => $application, 'applicationFile' => $file],
    );

    delete($url)->assertRedirect();

    expect(ApplicationFile::query()->count())->toBe(0);
    Storage::disk('s3')->assertMissing('applications/documents/1-abc.pdf');
})->group('applications');

it('refuses to delete a document of another application', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();
    $foreignFile = ApplicationFile::factory()->create();

    $url = URL::temporarySignedRoute(
        'de-ch.jobs.internship.application.files.destroy',
        now()->addDays(7),
        ['application' => $application, 'applicationFile' => $foreignFile],
    );

    delete($url)->assertNotFound();

    expect(ApplicationFile::query()->count())->toBe(1);
})->group('applications');

it('stores uploads under the file uuid, never the original name', function () {
    Storage::fake('s3');

    $application = Application::factory()->create();

    put(signedApplicationUrl($application), [
        'action' => 'save',
        'documents' => [UploadedFile::fake()->create('Mein Lebenslauf.pdf', 100, 'application/pdf')],
    ])->assertSessionHasNoErrors();

    $file = $application->files()->sole();

    expect($file->uuid)->not->toBeNull()
        ->and($file->path)->toBe("applications/documents/{$file->uuid}.pdf")
        ->and($file->path)->not->toContain('Lebenslauf')
        ->and($file->original_name)->toBe('Mein Lebenslauf.pdf');

    Storage::disk('s3')->assertExists($file->path);
})->group('applications');
