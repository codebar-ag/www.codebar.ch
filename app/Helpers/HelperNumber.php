<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Number;

class HelperNumber
{
    public function format(int|float|null $number, int $decimals = 2, string $decimalSeparator = '.', string $thousandSeparator = "'"): string
    {
        return $number
            ? number_format($number, $decimals, $decimalSeparator, $thousandSeparator)
            : self::defaultValue($decimals);
    }

    public function abbreviate(int|float|null $number, int $maxPrecision = 1): string
    {
        if ($number >= 1_000_000) {
            $abbreviated = Number::abbreviate($number, maxPrecision: $maxPrecision);

            if ($abbreviated !== false) {
                return $abbreviated;
            }
        }

        return $this->format($number, 0);
    }

    protected static function defaultValue(int $decimals = 0): string
    {
        return number_format(0, $decimals);
    }
}
