<?php

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use Database\Factories\NetworkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Network extends Model
{
    /** @use HasFactory<NetworkFactory> */
    use HasFactory;

    protected $casts = [
        'locale' => LocaleEnum::class,
        'category' => NetworkCategoryEnum::class,
        'status' => NetworkStatusEnum::class,
        'published' => 'boolean',
    ];

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

    public function websiteHost(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $host = parse_url($this->website, PHP_URL_HOST);

        return str_replace('www.', '', is_string($host) ? $host : $this->website);
    }
}
