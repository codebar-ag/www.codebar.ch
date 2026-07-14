<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Traits\HasLocalizedReferences;
use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\TechnologyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    /** @use HasFactory<TechnologyFactory> */
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
