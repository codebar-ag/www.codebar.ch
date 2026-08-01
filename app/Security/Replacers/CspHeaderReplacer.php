<?php

declare(strict_types=1);

namespace App\Security\Replacers;

use Spatie\ResponseCache\Replacers\Replacer;
use Symfony\Component\HttpFoundation\Response;

class CspHeaderReplacer implements Replacer
{
    /** @var list<string> */
    protected array $headers = [
        'Content-Security-Policy',
        'Content-Security-Policy-Report-Only',
    ];

    public function prepareResponseToCache(Response $response): void
    {
        foreach ($this->headers as $header) {
            $response->headers->remove($header);
        }
    }

    public function replaceInCachedResponse(Response $response): void
    {
        foreach ($this->headers as $header) {
            $response->headers->remove($header);
        }
    }
}
