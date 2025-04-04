<?php

namespace App\Checks;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class JobsCheck extends Check
{
    public const int TIME = 90;

    public function run(): Result
    {
        $count = DB::table('jobs')->count();

        $result = Result::make();
        $result->shortSummary("jobs table count: {$count}");

        if ($count <= 0) {
            return $result->ok();
        }

        $newCounts = Collection::times(self::TIME, function () {
            $count = DB::table('jobs')->count();
            sleep(1);

            return $count;
        });

        $allMatch = $newCounts->every(fn (int $value) => $value === $count);

        $result->shortSummary("jobs table count: {$allMatch}");

        if ($count != $allMatch) {
            return $result->ok();
        }

        return $result->failed();

    }
}
