<?php

namespace App\Traits;

use App\Models\Reference;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLocalizedReferences
{
    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'source');
    }
}
