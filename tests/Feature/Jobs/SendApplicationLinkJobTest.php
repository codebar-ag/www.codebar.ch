<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Jobs\Applications\SendApplicationLinkJob;
use App\Models\Application;
use App\Notifications\ApplicationLinkNotification;
use Illuminate\Support\Facades\Notification;

it('sends a signed application link to the matching application', function () {
    Notification::fake();

    $application = Application::factory()->create(['email' => 'mina@example.com']);

    (new SendApplicationLinkJob(Application::JOB_KEY_INTERNSHIP, 'mina@example.com', LocaleEnum::DE->value))->handle();

    Notification::assertSentTo(
        $application,
        ApplicationLinkNotification::class,
        function (ApplicationLinkNotification $notification) use ($application) {
            return str_contains($notification->url, 'signature=')
                && str_contains($notification->url, (string) $application->id)
                && str_contains($notification->url, 'stellen/praktikum/bewerbung');
        },
    );
})->group('applications');

it('sends nothing when no application matches', function () {
    Notification::fake();

    (new SendApplicationLinkJob(Application::JOB_KEY_INTERNSHIP, 'unknown@example.com', LocaleEnum::DE->value))->handle();

    Notification::assertNothingSent();
})->group('applications');

it('logs the application link notification to the notifications table', function () {
    $application = Application::factory()->create(['email' => 'mina@example.com']);

    (new SendApplicationLinkJob(Application::JOB_KEY_INTERNSHIP, 'mina@example.com', LocaleEnum::DE->value))->handle();

    expect($application->notifications()->where('type', ApplicationLinkNotification::class)->count())->toBe(1);
})->group('applications');
