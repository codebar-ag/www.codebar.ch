<?php

namespace App\Models;

use App\Contracts\HasTranslatedRouteKey;
use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasTranslatedRouteKey
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'teaser', 'content', 'slug', 'hero_caption', 'hero_alt'];

    protected $casts = [
        'tags' => 'json',
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'published' => 'boolean',
    ];

    /** The slug is translated, so route binding resolves it per locale. */
    public static function routeBindingIsTranslated(): bool
    {
        return true;
    }

    public function getLocale(): string
    {
        return app()->getLocale();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Deliberately not named author() — the legacy `author` string column would shadow
     * the relation on attribute access and silently return a name instead of a Contact.
     *
     * @return BelongsTo<Contact, $this>
     */
    public function authorContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /** The display name of the author, from the linked contact or the legacy column. */
    public function authorName(): ?string
    {
        $contact = $this->authorContact;

        if ($contact !== null) {
            return $contact->name;
        }

        return is_string($this->author) && $this->author !== '' ? $this->author : null;
    }

    /**
     * @return BelongsTo<NewsSeries, $this>
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(NewsSeries::class, 'series_id');
    }

    /**
     * @return BelongsToMany<NewsTag, $this>
     */
    public function newsTags(): BelongsToMany
    {
        return $this->belongsToMany(NewsTag::class, 'news_news_tag');
    }

    /**
     * Manually curated cross-links, kept unidirectional in the database but read both ways.
     *
     * @return BelongsToMany<News, $this>
     */
    public function relatedArticles(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_relations', 'news_id', 'related_news_id')
            ->withPivot('sort')
            ->orderBy('news_relations.sort');
    }

    /**
     * @param  Builder<News>  $query
     * @return Builder<News>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published', true);
    }

    /**
     * Live means both: a publication date and the `published` flag from the front
     * matter. Clearing the date would take an article offline too, but it would also
     * throw away the date the article is sorted and shown by.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published;
    }

    /**
     * The other parts of this article's series, ordered, published only.
     *
     * @return Collection<int, News>
     */
    public function seriesParts(): Collection
    {
        if ($this->series_id === null) {
            return new Collection;
        }

        /** @var Collection<int, News> */
        return self::query()
            ->published()
            ->where('series_id', $this->series_id)
            ->orderBy('series_position')
            ->get();
    }
}
