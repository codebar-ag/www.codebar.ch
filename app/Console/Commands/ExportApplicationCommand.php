<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ExportApplicationCommand extends Command
{
    protected $signature = 'applications:export
        {application : The ID of the application to export}
        {--dir= : Write the zip to a local directory instead of uploading it to S3}';

    protected $description = 'Export an application as a zip with a Markdown summary and all uploaded documents, uploaded to S3 with a signed download URL.';

    public function handle(): int
    {
        $application = Application::query()->with('files')->find($this->argument('application'));

        if (! $application instanceof Application) {
            $this->error('Application not found.');

            return self::FAILURE;
        }

        $slug = Str::slug($application->name()) ?: 'ohne-name';
        $filename = sprintf('bewerbung-%d-%s.zip', $application->id, $slug);

        $tempPath = tempnam(sys_get_temp_dir(), 'bewerbung');

        if ($tempPath === false || ! $this->writeZip($application, $tempPath)) {
            $this->error('Could not create the zip file.');

            return self::FAILURE;
        }

        if (is_string($directory = $this->option('dir'))) {
            File::ensureDirectoryExists($directory);
            File::move($tempPath, "{$directory}/{$filename}");

            $this->info("Exported application #{$application->id} to {$directory}/{$filename}");

            return self::SUCCESS;
        }

        $path = Application::EXPORTS_DIRECTORY."/{$filename}";

        $stream = fopen($tempPath, 'r');
        abort_if($stream === false, 500, 'Failed to read the zip file.');

        Storage::disk('s3')->put($path, $stream);
        fclose($stream);
        unlink($tempPath);

        $url = Storage::disk('s3')->temporaryUrl($path, now()->addDays(7));

        $this->info("Exported application #{$application->id} to s3:{$path}");
        $this->line('Download (valid for 7 days):');
        $this->line($url);

        return self::SUCCESS;
    }

    private function writeZip(Application $application, string $path): bool
    {
        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $zip->addFromString('bewerbung.md', $this->markdown($application));

        $usedNames = [];

        foreach ($application->files as $file) {
            $contents = Storage::disk($file->disk)->get($file->path);

            if ($contents === null) {
                $this->warn("Skipped missing file: {$file->original_name} ({$file->path})");

                continue;
            }

            $zip->addFromString('attachments/'.$this->uniqueName($file, $usedNames), $contents);
        }

        return $zip->close();
    }

    private function markdown(Application $application): string
    {
        $lines = [
            "# Bewerbung – {$application->name()}",
            '',
            '| Feld | Wert |',
            '| --- | --- |',
            "| ID | {$application->id} |",
            '| Stelle | '.($application->job_key ?? '–').' |',
            "| Status | {$application->status->value} |",
            '| Eingereicht am | '.($application->submitted_at?->format('d.m.Y H:i') ?? '–').' |',
            '| E-Mail | '.($application->email ?? '–').' |',
            '| Alter | '.($application->age ?? '–').' |',
            '| Ort | '.($application->city ?? '–').' |',
            '| GitHub | '.($application->github ?? '–').' |',
            '| LinkedIn | '.($application->linkedin ?? '–').' |',
            '| Projekt-Link | '.($application->project_link ?? '–').' |',
        ];

        $sections = [
            'Application question interests' => $application->interests,
            'Application question focus fit' => $application->focus_fit,
            'Application question built so far' => $application->built_so_far,
            'Application question about' => $application->about,
        ];

        foreach ($sections as $key => $value) {
            $lines[] = '';
            $lines[] = '## '.__($key, [], 'de_CH');
            $lines[] = '';
            $lines[] = blank($value) ? '–' : trim($value);
        }

        $lines[] = '';
        $lines[] = '## Anhänge';
        $lines[] = '';

        if ($application->files->isEmpty()) {
            $lines[] = '–';
        }

        foreach ($application->files as $file) {
            $lines[] = "- {$file->original_name} ({$file->humanSize()})";
        }

        $lines[] = '';

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, bool>  $usedNames
     */
    private function uniqueName(ApplicationFile $file, array &$usedNames): string
    {
        $name = basename($file->original_name);

        if (isset($usedNames[$name])) {
            $name = $file->uuid.'-'.$name;
        }

        $usedNames[$name] = true;

        return $name;
    }
}
