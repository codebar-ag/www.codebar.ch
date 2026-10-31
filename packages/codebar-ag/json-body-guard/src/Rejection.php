<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard;

final readonly class Rejection
{
    public function __construct(
        public Reason $reason,
        public string $path,
    ) {}

    public function status(): int
    {
        return $this->reason->status();
    }

    public function message(): string
    {
        return $this->reason->message();
    }

    public function send(): void
    {
        http_response_code($this->status());
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        echo $this->message();
    }
}
