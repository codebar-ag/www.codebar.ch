<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

trait HasActivity
{
    use CausesActivity;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function subjects(): MorphMany
    {
        /** @phpstan-ignore-next-line */
        return $this->morphMany(
            /** @phpstan-ignore-next-line */
            related: ActivitylogServiceProvider::determineActivityModel(),
            name: 'subject'
        );
    }
}
