<?php

declare(strict_types=1);

namespace App\Jobs\Network;

use App\Models\NetworkUser;
use App\Notifications\NetworkManageLinkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class SendNetworkManageLinkJob implements ShouldQueue
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
     * The requester is told nothing either way, so a silent failure here looks to them
     * like a link that never arrives. Log it with a hashed address — the plain one is
     * exactly the kind of thing that should not end up in a log file.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Failed to send a network manage link.', [
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
            now()->addHour(),
            ['networkUser' => $networkUser],
        );

        $networkUser->notify(
            (new NetworkManageLinkNotification($url))->locale($this->locale),
        );
    }
}
