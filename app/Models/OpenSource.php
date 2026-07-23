<?php

namespace App\Models;

use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\OpenSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OpenSource extends Model
{
    /** @use HasFactory<OpenSourceFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'teaser', 'content'];

    protected $casts = [
        'published' => 'boolean',
        'tags' => 'json',
        'downloads' => 'int',
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
