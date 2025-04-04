<?php

namespace App\Helpers;

class HelperNumber
{
    public function format(int|float|null $number, int $decimals = 2, string $decimalSeparator = '.', string $thousandSeparator = "'"): string
    {
        return $number
            ? number_format($number, $decimals, $decimalSeparator, $thousandSeparator)
            : self::defaultValue($decimals);
    }

    protected static function defaultValue(int $decimals = 0): string
    {
        return number_format(0, $decimals);
    }
}
