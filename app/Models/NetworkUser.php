<?php

namespace App\Models;

use Database\Factories\NetworkUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NetworkUser extends Model
{
    /** @use HasFactory<NetworkUserFactory> */
    use HasFactory;

    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * The company rows of this person, one per locale.
     *
     * @return HasMany<Network, $this>
     */
    public function networks(): HasMany
    {
        return $this->hasMany(Network::class, 'key', 'network_key');
    }

    /**
     * The company row for the given (or current) locale.
     */
    public function network(?string $locale = null): ?Network
    {
        return $this->networks()
            ->where('locale', $locale ?? app()->getLocale())
            ->first();
    }

    /**
     * @param  Builder<NetworkUser>  $query
     * @return Builder<NetworkUser>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    }
}
