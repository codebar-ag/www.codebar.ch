<?php

declare(strict_types=1);

namespace App\Helpers\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Helpers\HelperDate
 *
 * @method static string formatDateTime(Carbon $date, string|null $locale = null)
 * @method static string formatDate(Carbon $date)
 * @method static string monthLabel(string $yearMonth)
 * @method static string monthName(int $month)
 */
class HelperDate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Helpers\HelperDate::class;
    }
}
