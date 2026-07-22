<?php

namespace App\Models;

use App\Support\CloudinaryUrl;
use App\Support\GravatarUrl;
use Database\Factories\NetworkUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class NetworkUser extends Model
{
    /** @use HasFactory<NetworkUserFactory> */
    use HasFactory;

    use Notifiable;

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

    /**
     * The image to display for this person: avatar_url first,
     * then Gravatar, then the neutral placeholder.
     */
    public function avatarDisplayUrl(int $size): string
    {
        if ($this->avatar_url) {
            return CloudinaryUrl::src($this->avatar_url, $size);
        }

        if ($this->email) {
            return GravatarUrl::src($this->email, $size);
        }

        return '/images/placeholders/avatar-sample.svg';
    }
}
