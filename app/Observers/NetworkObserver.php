<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\CacheKeyEnum;
use App\Models\Network;
use App\Support\ResponseCacheFlusher;
use Illuminate\Support\Facades\Cache;

class NetworkObserver
{
    public function saved(Network $network): void
    {
        self::flush();
    }

    public function deleted(Network $network): void
    {
        self::flush();
    }

    public static function flush(): void
    {
        Cache::forget(CacheKeyEnum::NETWORKS_PUBLISHED->value);

        ResponseCacheFlusher::flush();
    }
}
