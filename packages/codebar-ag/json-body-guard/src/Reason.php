<?php

declare(strict_types=1);

namespace CodebarAg\JsonBodyGuard;

enum Reason: string
{
    case PathNotAllowed = 'path_not_allowed';
    case TooLarge = 'too_large';

    public function status(): int
    {
        return match ($this) {
            self::PathNotAllowed => 415,
            self::TooLarge => 413,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::PathNotAllowed => 'Unsupported Media Type',
            self::TooLarge => 'Payload Too Large',
        };
    }
}
