<?php

namespace App\Jobs\Network;

use App\Models\NetworkUser;
use App\Notifications\NetworkInviteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SendNetworkInviteJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $locale,
    ) {}

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
