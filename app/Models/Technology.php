<?php

namespace App\Models;

use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\TechnologyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Technology extends Model
{
    /** @use HasFactory<TechnologyFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'teaser', 'content'];

    protected $casts = [
        'published' => 'boolean',
        'tags' => 'json',
    ];

    public function getLocale(): string
    {
        return app()->getLocale();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
