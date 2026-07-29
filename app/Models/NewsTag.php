<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

/**
 * A browsable topic. The denormalised `news.tags` JSON column stays the display and
 * schema.org keyword source; this table exists so a topic can carry its own title,
 * description and URL.
 */
class NewsTag extends Model
{
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'slug', 'description'];

    /**
     * @return BelongsToMany<News, $this>
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_news_tag');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
