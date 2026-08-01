<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\NetworkUser;

class NetworkUserObserver
{
    /** The cached network listing eager loads publishedUsers, so a person's edit stales it. */
    public function saved(NetworkUser $networkUser): void
    {
        NetworkObserver::flush();
    }

    public function deleted(NetworkUser $networkUser): void
    {
        NetworkObserver::flush();
    }
}
