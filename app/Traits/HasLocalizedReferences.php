<?php

namespace App\Traits;

use App\Models\Reference;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLocalizedReferences
{
    /**
     * @return MorphMany<Reference, $this>
     */
    public function references(): MorphMany
    {
        return $this->morphMany(related: Reference::class, name: 'source');
    }
}
