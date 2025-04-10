<?php

namespace App\Security\Generator;

use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class LaravelViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        $vite = Vite::cspNonce();
        ray($vite);

        return $vite;
    }
}
