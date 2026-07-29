<?php

declare(strict_types=1);

namespace App\Security\Generator;

use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class LaravelViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        return Vite::cspNonce() ?? Vite::useCspNonce();
    }
}
