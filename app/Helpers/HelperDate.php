<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use InvalidArgumentException;

class HelperDate
{
    public function formatDateTime(Carbon $date, ?string $locale = null): string
    {
        return match ($locale ?? app()->getLocale()) {
            'de', 'de_CH' => $date->format('d.m.Y H:i').' Uhr',
            'en', 'en_CH' => $date->format('d.m.Y g:i a'),
            default => $date->format('d.m.Y H:i')
        };
    }

    public function formatDate(Carbon $date): string
    {
        return $date->format('d.m.Y');
    }

    public function monthLabel(string $yearMonth): string
    {
        $date = Carbon::createFromFormat('!Y-m', $yearMonth);

        if ($date === null) {
            throw new InvalidArgumentException("Invalid year-month value: {$yearMonth}");
        }

        $date->locale(app()->getLocale());

        return $date->translatedFormat('F Y');
    }

    public function monthName(int $month): string
    {
        $date = Carbon::create(month: $month);

        if ($date === null) {
            throw new InvalidArgumentException("Invalid month value: {$month}");
        }

        $date->locale(app()->getLocale());

        return $date->translatedFormat('F');
    }
}
