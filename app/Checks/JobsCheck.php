<?php

declare(strict_types=1);

namespace App\Checks;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

/**
 * A queue is healthy when it is either empty or moving. Depth alone says nothing —
 * a thousand jobs dispatched a second ago is fine, one job stuck for an hour is not.
 * The age of the oldest pending job is what separates the two, and it costs one query
 * rather than sampling the depth once a second for a minute and a half.
 */
class JobsCheck extends Check
{
    public const int STALLED_AFTER_MINUTES = 5;

    public function run(): Result
    {
        $pending = DB::table('jobs')->count();

        $result = Result::make()->shortSummary("pending jobs: {$pending}");

        if ($pending === 0) {
            return $result->ok();
        }

        $waitingMinutes = $this->oldestPendingJobAgeInMinutes();

        if ($waitingMinutes === null || $waitingMinutes < self::STALLED_AFTER_MINUTES) {
            return $result->ok();
        }

        return $result->failed(
            "The queue is not draining: {$pending} pending job(s), the oldest waiting {$waitingMinutes} minute(s)."
        );
    }

    private function oldestPendingJobAgeInMinutes(): ?int
    {
        $availableAt = DB::table('jobs')->min('available_at');

        if (! is_numeric($availableAt)) {
            return null;
        }

        return (int) Carbon::createFromTimestamp((int) $availableAt)->diffInMinutes(absolute: true);
    }
}
