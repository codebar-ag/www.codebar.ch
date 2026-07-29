<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\NetworkUser;
use Spatie\ResponseCache\Facades\ResponseCache;

class NetworkUserObserver
{
    public function saved(NetworkUser $networkUser): void
    {
        ResponseCache::clear();
    }

    public function deleted(NetworkUser $networkUser): void
    {
        ResponseCache::clear();
    }
}
