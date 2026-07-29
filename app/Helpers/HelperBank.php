<?php

declare(strict_types=1);

namespace App\Helpers;

class HelperBank
{
    public function formatIban(?string $iban): ?string
    {
        return $iban ?
            trim(chunk_split($iban, 4, ' '))
            : null;
    }
}
