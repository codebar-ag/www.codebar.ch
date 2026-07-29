<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchLlmUsageJob;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Spatie\ResponseCache\Facades\ResponseCache;

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

        // Batched rather than dispatched one by one: the usage figures are rendered
        // into the AI pages, so the cached HTML has to go once the numbers change.
        // finally() fires after the last day is stored — clearing per job would wipe
        // the whole site's response cache once per day in the window, and clearing
        // here in the command would fire before the queue had done any work at all.
        Bus::batch(
            $days->map(fn (CarbonInterface $day): FetchLlmUsageJob => new FetchLlmUsageJob(CarbonImmutable::instance($day)))->all()
        )
            ->name('llm-usage-sync')
            ->finally(function (Batch $batch): void {
                ResponseCache::clear();
            })
            ->dispatch();

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
