<?php

declare(strict_types=1);

use App\Enums\ApplicationStatusEnum;
use App\Enums\JobPositionStatusEnum;
use App\Models\Application;
use App\Models\JobPosition;
use App\Notifications\ApplicationLinkNotification;
use App\Notifications\ApplicationReceivedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('walks an applicant from the fair to a submitted application', function () {
    Notification::fake();
    Storage::fake('s3');

    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
        'route_name' => 'jobs.internship.show',
    ]);

    get(route('de-ch.jobs.index'))
        ->assertOk()
        ->assertSee(route('de-ch.jobs.internship.show'));

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com'])
        ->assertRedirect(route('de-ch.jobs.internship.show'));

    $application = Application::query()->sole();

    $link = '';
    Notification::assertSentTo(
        $application,
        ApplicationLinkNotification::class,
        function (ApplicationLinkNotification $notification) use (&$link) {
            $link = $notification->url;

            return true;
        },
    );

    get($link)
        ->assertOk()
        ->assertSee('mina@example.com');

    patch($link, ['about' => 'Ich spiele Handball und baue kleine Websites.'])
        ->assertOk()
        ->assertJsonStructure(['saved_at']);

    put($link, [
        'action' => 'save',
        'first_name' => 'Mina',
        'last_name' => 'Keller',
        'documents' => [UploadedFile::fake()->create('lebenslauf.pdf', 120, 'application/pdf')],
    ])->assertSessionHasNoErrors();

    expect($application->refresh()->status)->toBe(ApplicationStatusEnum::Draft)
        ->and($application->about)->toBe('Ich spiele Handball und baue kleine Websites.')
        ->and($application->files()->count())->toBe(1);

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com']);

    expect(Application::query()->count())->toBe(1);

    put($link, [
        'action' => 'submit',
        'first_name' => 'Mina',
        'last_name' => 'Keller',
        'age' => 16,
        'city' => 'Basel',
        'interests' => 'Web-Entwicklung und Open Source.',
        'focus_fit' => 'Laravel klingt spannend, Games eher nicht.',
        'about' => 'Ich spiele Handball und baue kleine Websites.',
    ])->assertSessionHasNoErrors();

    $application->refresh();

    expect($application->status)->toBe(ApplicationStatusEnum::Submitted)
        ->and($application->submitted_at)->not->toBeNull();

    Notification::assertSentTo($application, ApplicationSubmittedNotification::class);
    Notification::assertSentOnDemand(ApplicationReceivedNotification::class);

    get($link)
        ->assertOk()
        ->assertDontSee('<form', false)
        ->assertSee(__('Application locked header'));

    put($link, ['action' => 'save', 'first_name' => 'Anders'])->assertForbidden();

    post(route('de-ch.jobs.internship.request.store'), ['email' => 'mina@example.com']);

    expect(Application::query()->count())->toBe(1)
        ->and($application->refresh()->first_name)->toBe('Mina');
})->group('applications');
