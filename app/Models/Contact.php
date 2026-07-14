<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected $casts = [
        'published' => 'boolean',
        'sections' => 'json',
        'icons' => 'json',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => self::clearPublishedCache());
        static::deleted(fn () => self::clearPublishedCache());
    }

    public static function clearPublishedCache(): void
    {
        foreach (LocaleEnum::cases() as $locale) {
            Cache::forget(Str::slug("contacts_published_{$locale->value}"));
        }
    }
}
