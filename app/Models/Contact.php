<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CacheKeyEnum;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'key',
        'published',
        'sort',
        'name',
        'sections',
        'image',
        'icons',
    ];

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
        foreach (CacheKeyEnum::CONTACTS_PUBLISHED->forAllLocales() as $key) {
            Cache::forget($key);
        }
    }
}
