<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Traits\HasLocalizedReferences;
use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    use HasLocalizedReferences;
    use HasLocalizedRouteBinding;

    protected $casts = [
        'published' => 'boolean',
        'locale' => LocaleEnum::class,
        'tags' => 'json',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
