<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HelperDevice
{
    public function isMobileDevice(): bool
    {
        return Arr::has($_SERVER, 'HTTP_USER_AGENT') && Str::contains($_SERVER['HTTP_USER_AGENT'], ['mobile', 'Mobile']);
    }
}
