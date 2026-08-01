<?php

declare(strict_types=1);

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Helpers\HelperNumber
 *
 * @method static string format(int|float|null $number, int $decimals = 2, string $decimalSeparator = '.', string $thousandSeparator = "'")
 * @method static string abbreviate(int|float|null $number, int $maxPrecision = 1)
 */
class HelperNumber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Helpers\HelperNumber::class;
    }
}
