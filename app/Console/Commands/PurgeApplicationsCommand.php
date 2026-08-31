<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PurgeApplicationsCommand extends Command
{
    protected $signature = 'applications:purge {--force : Skip the confirmation prompt}';

    protected $description = 'Permanently delete all applications, their uploaded documents, related notifications and local export zips.';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('This permanently deletes ALL applications, uploaded documents, related notifications and export zips. Continue?')) {
            $this->info('Aborted.');

            return self::SUCCESS;
        }

        $applications = Application::query()->with('files')->get();

        foreach ($applications as $application) {
            foreach ($application->files as $file) {
                $file->deleteFromDisk();
            }
        }

        $files = ApplicationFile::query()->count();
        $deleted = $applications->count();

        ApplicationFile::query()->delete();
        Application::query()->delete();

        $notifications = DB::table('notifications')
            ->where('notifiable_type', Application::class)
            ->delete();

        Storage::disk('s3')->deleteDirectory('applications');

        File::deleteDirectory(storage_path('app/exports'));

        $this->info("Purged {$deleted} applications, {$files} documents and {$notifications} notifications.");

        return self::SUCCESS;
    }
}
