<?php

declare(strict_types=1);

namespace App\Jobs\Network;

use App\Models\NetworkUser;
use App\Notifications\NetworkManageLinkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SendNetworkManageLinkJob implements ShouldQueue
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
            now()->addHour(),
            ['networkUser' => $networkUser],
        );

        $networkUser->notify(
            (new NetworkManageLinkNotification($url))->locale($this->locale),
        );
    }
}
