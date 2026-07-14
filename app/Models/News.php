<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Traits\HasLocalizedReferences;
use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    use HasLocalizedReferences;
    use HasLocalizedRouteBinding;

    protected $casts = [
        'locale' => LocaleEnum::class,
        'tags' => 'json',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
