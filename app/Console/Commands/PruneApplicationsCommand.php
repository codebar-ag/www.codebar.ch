<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ApplicationStatusEnum;
use App\Models\Application;
use Illuminate\Console\Command;

class PruneApplicationsCommand extends Command
{
    protected $signature = 'applications:prune';

    protected $description = 'Delete draft applications untouched for six months, including their uploaded documents.';

    public function handle(): int
    {
        $applications = Application::query()
            ->where('status', ApplicationStatusEnum::Draft)
            ->where('updated_at', '<', now()->subMonths(6))
            ->with('files')
            ->get();

        foreach ($applications as $application) {
            foreach ($application->files as $file) {
                $file->deleteFromDisk();
            }

            $application->deleteExportsFromDisk();
            $application->delete();
        }

        $this->info("Pruned {$applications->count()} stale draft applications.");

        return self::SUCCESS;
    }
}
