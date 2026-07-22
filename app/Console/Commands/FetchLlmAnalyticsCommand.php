<?php

namespace App\Console\Commands;

use App\Jobs\FetchLlmUsageJob;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class FetchLlmAnalyticsCommand extends Command
{
    private const string INITIAL_SYNC_START = '2026-01-01';

    protected $signature = 'llm:fetch-analytics
        {--full : Full sync from the very beginning ('.self::INITIAL_SYNC_START.')}
        {--from= : Start date (YYYY-MM-DD), defaults to 3 days ago}
        {--to= : End date (YYYY-MM-DD), defaults to today}';

    protected $description = 'Fetch daily per-model LLM usage from the LiteLLM proxy and store it locally';

    public function handle(): int
    {
        $to = $this->date($this->option('to')) ?? CarbonImmutable::today();
        $from = $this->date($this->option('from'))
            ?? ($this->option('full') ? $this->date(self::INITIAL_SYNC_START) : $to->subDays(3));

        if ($from->greaterThan($to)) {
            $this->error('The --from date must not be after the --to date.');

            return self::FAILURE;
        }

        $days = collect(CarbonPeriod::create($from, $to)->toArray());

        $days->each(fn (CarbonInterface $day) => FetchLlmUsageJob::dispatch(CarbonImmutable::instance($day)));

        $this->info("Dispatched {$days->count()} job(s) for {$from->toDateString()} to {$to->toDateString()}.");

        return self::SUCCESS;
    }

    /**
     * @return ($value is null ? null : CarbonImmutable)
     */
    private function date(?string $value): ?CarbonImmutable
    {
        return $value === null
            ? null
            : CarbonImmutable::createFromFormat('!Y-m-d', $value);
    }
}
