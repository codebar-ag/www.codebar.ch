<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $casts = [
        'published' => 'boolean',
        'locale' => LocaleEnum::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
