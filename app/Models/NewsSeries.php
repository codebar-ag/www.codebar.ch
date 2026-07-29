<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * An ordered, named sequence of articles — "DMS-Migration, Teil 2 von 5".
 */
class NewsSeries extends Model
{
    use HasTranslations;

    /** @var list<string> */
    protected $fillable = [
        'key',
        'title',
        'slug',
        'description',
        'published',
    ];

    protected $table = 'news_series';

    /** @var array<int, string> */
    protected array $translatable = ['title', 'slug', 'description'];

    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * @return HasMany<News, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(News::class, 'series_id')->orderBy('series_position');
    }

    /**
     * @return HasMany<News, $this>
     */
    public function publishedArticles(): HasMany
    {
        return $this->articles()->published();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
