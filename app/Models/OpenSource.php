<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Traits\HasLocalizedReferences;
use App\Traits\HasLocalizedRouteBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenSource extends Model
{
    use HasFactory;
    use HasLocalizedReferences;
    use HasLocalizedRouteBinding;

    protected $casts = [
        'published' => 'boolean',
        'locale' => LocaleEnum::class,
        'tags' => 'json',
        'downloads' => 'int',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
