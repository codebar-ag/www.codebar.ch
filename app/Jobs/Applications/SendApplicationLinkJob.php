<?php

declare(strict_types=1);

namespace App\Jobs\Applications;

use App\Models\Application;
use App\Notifications\ApplicationLinkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class SendApplicationLinkJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public string $jobKey,
        public string $email,
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
        Log::error('Failed to send an application link.', [
            'email_sha256' => hash('sha256', $this->email),
            'job_key' => $this->jobKey,
            'locale' => $this->locale,
            'exception' => $exception->getMessage(),
        ]);
    }

    public function handle(): void
    {
        $application = Application::query()
            ->where('job_key', $this->jobKey)
            ->where('email', $this->email)
            ->first();

        if (! $application) {
            return;
        }

        $url = URL::temporarySignedRoute(
            Str::slug($this->locale).'.jobs.internship.application.show',
            now()->addDays(7),
            ['application' => $application],
        );

        $application->notify(
            (new ApplicationLinkNotification($url))->locale($this->locale),
        );
    }
}
