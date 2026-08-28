<?php

declare(strict_types=1);

namespace App\Jobs\Applications;

use App\Models\Application;
use App\Notifications\ApplicationReceivedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class SendApplicationSubmittedJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public int $applicationId,
        public string $locale,
    ) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300];
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Failed to send the application submitted notifications.', [
            'application_id' => $this->applicationId,
            'locale' => $this->locale,
            'exception' => $exception->getMessage(),
        ]);
    }

    public function handle(): void
    {
        $application = Application::query()->with('files')->find($this->applicationId);

        if (! $application || ! $application->isSubmitted()) {
            return;
        }

        $url = URL::temporarySignedRoute(
            Str::slug($this->locale).'.jobs.internship.application.show',
            now()->addDays(7),
            ['application' => $application],
        );

        $application->notify(
            (new ApplicationSubmittedNotification($url))->locale($this->locale),
        );

        Notification::route('mail', config()->string('company.email'))->notify(
            (new ApplicationReceivedNotification($application))->locale('de_CH'),
        );
    }
}
