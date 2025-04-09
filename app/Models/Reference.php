<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reference extends Model
{
    protected $fillable = [
        'source_type',
        'source_id',
        'reference_type',
        'reference_id',
        'reference_locale',
    ];

    public function source(): MorphTo
    {
        return $this->morphTo('source');
    }

    public function target(): MorphTo
    {
        return $this->morphTo('reference');
    }
}
