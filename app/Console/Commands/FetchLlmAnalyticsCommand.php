<?php

namespace App\Console\Commands;

use App\Jobs\FetchLlmUsageJob;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Console\Command;

class FetchLlmAnalyticsCommand extends Command
{
    protected $signature = 'llm:fetch-analytics
        {--from= : Start date (YYYY-MM-DD), defaults to 3 days ago}
        {--to= : End date (YYYY-MM-DD), defaults to today}';

    protected $description = 'Fetch daily per-model LLM usage from the LiteLLM proxy and store it locally';

    public function handle(): int
    {
        try {
            $to = $this->date($this->option('to')) ?? CarbonImmutable::today();
            $from = $this->date($this->option('from')) ?? $to->subDays(3);
        } catch (InvalidFormatException) {
            $this->error('Invalid date format, expected YYYY-MM-DD.');

            return self::FAILURE;
        }

        if ($from->greaterThan($to)) {
            $this->error('The --from date must not be after the --to date.');

            return self::FAILURE;
        }

        $days = collect(CarbonPeriod::create($from, $to));

        $days->each(fn (mixed $day) => FetchLlmUsageJob::dispatch(CarbonImmutable::instance($day)));

        $this->info("Dispatched {$days->count()} job(s) for {$from->toDateString()} to {$to->toDateString()}.");

        return self::SUCCESS;
    }

    private function date(?string $value): ?CarbonImmutable
    {
        return $value === null
            ? null
            : CarbonImmutable::createFromFormat('!Y-m-d', $value);
    }
}
