<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard\Tests\Support;

final readonly class Response
{
    public function __construct(
        public int $status,
        public string $body,
    ) {}
}
