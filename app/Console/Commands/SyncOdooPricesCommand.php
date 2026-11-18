<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\OdooPriceSnapshot;
use App\Support\OdooPriceSource;
use App\Support\ResponseCacheFlusher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncOdooPricesCommand extends Command
{
    protected $signature = 'odoo:sync-prices';

    protected $description = 'Read the prices published on odoo.com and odoo.sh and use them on the Odoo pricing page.';

    public function handle(OdooPriceSource $source): int
    {
        $baseline = OdooPriceSnapshot::configured();

        try {
            $published = [...$source->license(), ...$source->hosting()];
        } catch (Throwable $exception) {
            return $this->reject([$exception->getMessage()]);
        }

        $problems = $this->problems($published, $baseline);

        if ($problems !== []) {
            return $this->reject($problems);
        }

        $latest = OdooPriceSnapshot::query()->latest('id')->first();

        if ($latest instanceof OdooPriceSnapshot && $latest->prices == $published) {
            $latest->touch();
            $this->components->info('The Odoo prices are unchanged.');

            return self::SUCCESS;
        }

        $previous = $latest instanceof OdooPriceSnapshot ? $latest->prices : $baseline;

        OdooPriceSnapshot::query()->create(['prices' => $published]);

        foreach ($published as $key => $value) {
            if (($previous[$key] ?? null) !== $value) {
                $this->components->twoColumnDetail($key, sprintf('%s → %d', $previous[$key] ?? '–', $value));
            }
        }

        if ($previous != $published) {
            ResponseCacheFlusher::flush();
            Log::info('Odoo prices updated from the published pages.', ['from' => $previous, 'to' => $published]);
        }

        $this->components->info('The Odoo prices are stored.');

        return self::SUCCESS;
    }

    /**
     * @param  array<string, int>  $published
     * @param  array<string, int>  $baseline
     * @return array<int, string>
     */
    private function problems(array $published, array $baseline): array
    {
        $problems = [];

        foreach (OdooPriceSnapshot::KEYS as $key) {
            $value = $published[$key] ?? null;
            $reference = $baseline[$key];

            if ($value === null) {
                $problems[] = sprintf('%s is missing.', $key);

                continue;
            }

            [$lowest, $highest] = $key === 'hosting.factor.yearly'
                ? [50, 100]
                : [(int) floor($reference / 2), $reference * 2];

            if ($value < max(1, $lowest) || $value > $highest) {
                $problems[] = sprintf('%s is %d, outside the plausible range %d to %d.', $key, $value, $lowest, $highest);
            }
        }

        return $problems;
    }

    /**
     * @param  array<int, string>  $problems
     */
    private function reject(array $problems): int
    {
        foreach ($problems as $problem) {
            $this->components->warn($problem);
        }

        $this->components->info('The last valid Odoo prices stay in use.');

        Log::info('Odoo prices were not updated; the last valid prices stay in use.', ['problems' => $problems]);

        return self::SUCCESS;
    }
}
