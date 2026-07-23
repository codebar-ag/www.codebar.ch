<?php

namespace App\Models;

use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'teaser', 'content'];

    protected $casts = [
        'tags' => 'json',
        'published_at' => 'datetime',
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
