<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Jobs\Applications\SendApplicationSubmittedJob;
use App\Models\Application;
use App\Notifications\ApplicationReceivedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

it('sends the confirmation to the applicant and the summary to codebar', function () {
    Notification::fake();

    $application = Application::factory()->submitted()->create();

    (new SendApplicationSubmittedJob($application->id, LocaleEnum::DE->value))->handle();

    Notification::assertSentTo(
        $application,
        ApplicationSubmittedNotification::class,
        fn (ApplicationSubmittedNotification $notification) => str_contains($notification->url, 'signature=')
            && str_contains($notification->url, 'stellen/praktikum/bewerbung'),
    );

    Notification::assertSentOnDemand(
        ApplicationReceivedNotification::class,
        fn (ApplicationReceivedNotification $notification, array $channels, AnonymousNotifiable $notifiable) => $notifiable->routes['mail'] === config()->string('company.email')
            && $notification->application->is($application),
    );
})->group('applications');

it('sends nothing for a missing or unsubmitted application', function () {
    Notification::fake();

    $draft = Application::factory()->create();

    (new SendApplicationSubmittedJob($draft->id, LocaleEnum::DE->value))->handle();
    (new SendApplicationSubmittedJob(999999, LocaleEnum::DE->value))->handle();

    Notification::assertNothingSent();
})->group('applications');

it('logs the confirmation to the notifications table', function () {
    $application = Application::factory()->submitted()->create();

    (new SendApplicationSubmittedJob($application->id, LocaleEnum::DE->value))->handle();

    expect($application->notifications()->where('type', ApplicationSubmittedNotification::class)->count())->toBe(1);
})->group('applications');
