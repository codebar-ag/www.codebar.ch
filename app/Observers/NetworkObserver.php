<?php

namespace App\Observers;

use App\Models\Network;
use Spatie\ResponseCache\Facades\ResponseCache;

class NetworkObserver
{
    public function saved(Network $network): void
    {
        ResponseCache::clear();
    }

    public function deleted(Network $network): void
    {
        ResponseCache::clear();
    }
}
