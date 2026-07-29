<?php

declare(strict_types=1);

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

    /** @var list<string> */
    protected $fillable = [
        'published',
        'group',
        'order',
        'title',
        'slug',
        'teaser',
        'content',
        'image',
        'tags',
        'link',
    ];

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
