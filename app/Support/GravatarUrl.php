<?php

declare(strict_types=1);

namespace App\Support;

class GravatarUrl
{
    public static function src(string $email, int $size): string
    {
        $hash = hash('sha256', mb_strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    }
}
