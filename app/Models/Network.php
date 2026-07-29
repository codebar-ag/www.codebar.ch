<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use Database\Factories\NetworkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Network extends Model
{
    /** @use HasFactory<NetworkFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['name', 'excerpt', 'tier_label'];

    /** @var list<string> */
    protected $fillable = [
        'key',
        'name',
        'category',
        'status',
        'cover_disk',
        'cover_path',
        'cover_url',
        'tier_label',
        'excerpt',
        'website',
        'since_year',
        'until_year',
        'page_slug',
        'published',
        'sort',
    ];

    protected $casts = [
        'category' => NetworkCategoryEnum::class,
        'status' => NetworkStatusEnum::class,
        'published' => 'boolean',
    ];

    public function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * All contact persons of this company, shared across locales.
     *
     * @return HasMany<NetworkUser, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(NetworkUser::class, 'network_key', 'key')->orderBy('sort');
    }

    /**
     * Published contact persons of this company, shared across locales.
     *
     * @return HasMany<NetworkUser, $this>
     */
    public function publishedUsers(): HasMany
    {
        return $this->users()->where('published', true);
    }

    /**
     * @param  Builder<Network>  $query
     * @return Builder<Network>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * @param  Builder<Network>  $query
     * @return Builder<Network>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', NetworkStatusEnum::ACTIVE);
    }

    /**
     * The company image to display: cover_url or the placeholder — no Gravatar.
     */
    public function coverDisplayUrl(): string
    {
        return $this->cover_url ?? '/images/placeholders/network-company.svg';
    }

    public function websiteHost(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $host = parse_url($this->website, PHP_URL_HOST);

        return str_replace('www.', '', is_string($host) ? $host : $this->website);
    }
}
