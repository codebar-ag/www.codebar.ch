<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HelperDevice
{
    public function isMobileDevice(): bool
    {
        $userAgent = Arr::get($_SERVER, 'HTTP_USER_AGENT');

        return is_string($userAgent) && Str::contains($userAgent, ['mobile', 'Mobile']);
    }
}
