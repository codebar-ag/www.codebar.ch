<?php

declare(strict_types=1);

namespace App\Jobs\Network;

use App\Models\NetworkUser;
use App\Notifications\NetworkInviteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class SendNetworkInviteJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
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

    /**
     * Invites go out in bulk from an interactive command, so a failure that only shows
     * up in the failed_jobs table is a partner nobody notices was never invited.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Failed to send a network invite.', [
            'email_sha256' => hash('sha256', $this->email),
            'locale' => $this->locale,
            'exception' => $exception->getMessage(),
        ]);
    }

    public function handle(): void
    {
        $networkUser = NetworkUser::query()
            ->where('email', $this->email)
            ->first();

        if (! $networkUser) {
            return;
        }

        $url = URL::temporarySignedRoute(
            Str::slug($this->locale).'.network.manage.show',
            now()->addHours(96),
            ['networkUser' => $networkUser],
        );

        $networkUser->notify(
            (new NetworkInviteNotification(
                $url,
                is_string($name = $networkUser->network?->getTranslation('name', $this->locale)) ? $name : $networkUser->network_key,
            ))->locale($this->locale),
        );
    }
}
