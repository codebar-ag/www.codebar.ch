<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Notifications\OdooPricesChangedNotification;
use App\Support\OdooPriceSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class CheckOdooPricesCommand extends Command
{
    protected $signature = 'odoo:check-prices';

    protected $description = 'Compare config/odoo-pricing.php with the prices published on odoo.com and odoo.sh.';

    public function handle(OdooPriceSource $source): int
    {
        $findings = [];

        foreach (['license', 'hosting'] as $group) {
            try {
                $published = $source->{$group}();
            } catch (Throwable $exception) {
                $findings[] = $exception->getMessage();

                continue;
            }

            foreach ($published as $key => $value) {
                $configured = $this->configured($key);

                $this->components->twoColumnDetail($key, sprintf('%d / %d', $configured, $value));

                if ($configured !== $value) {
                    $findings[] = sprintf('%s: config %d, published %d.', $key, $configured, $value);
                }
            }
        }

        if ($findings === []) {
            $this->components->info('The configured Odoo prices match the published ones.');

            return self::SUCCESS;
        }

        foreach ($findings as $finding) {
            $this->components->error($finding);
        }

        Log::warning('Odoo prices differ from config/odoo-pricing.php.', ['findings' => $findings]);

        $recipient = config('odoo-pricing.notify');

        if (is_string($recipient) && $recipient !== '') {
            Notification::route('mail', $recipient)->notify(new OdooPricesChangedNotification($findings));
        }

        return self::FAILURE;
    }

    private function configured(string $key): int
    {
        $value = config('odoo-pricing.'.$key);

        if ($key === 'hosting.factor.yearly') {
            return (int) round((is_numeric($value) ? (float) $value : 0.0) * 100);
        }

        return is_numeric($value) ? (int) $value : 0;
    }
}
