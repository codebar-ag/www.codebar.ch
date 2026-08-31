<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

it('exports an application to s3 and outputs a signed download url', function () {
    Storage::fake('s3');
    Storage::disk('s3')->buildTemporaryUrlsUsing(
        fn (string $path): string => "https://s3.example/signed/{$path}",
    );
    Storage::disk('s3')->put('applications/documents/cv.pdf', 'pdf-content');

    $application = Application::factory()->submitted()->create([
        'first_name' => 'Mira',
        'last_name' => 'Muster',
        'about' => 'Ich baue gerne Dinge.',
    ]);

    ApplicationFile::factory()->create([
        'application_id' => $application->id,
        'path' => 'applications/documents/cv.pdf',
        'original_name' => 'Lebenslauf.pdf',
    ]);

    $zipPath = "applications/exports/bewerbung-{$application->id}-mira-muster.zip";

    runArtisan('applications:export', ['application' => $application->id])
        ->expectsOutputToContain("https://s3.example/signed/{$zipPath}")
        ->assertSuccessful();

    Storage::disk('s3')->assertExists($zipPath);

    $zip = new ZipArchive;
    $zip->open(Storage::disk('s3')->path($zipPath));

    expect($zip->getFromName('bewerbung.md'))
        ->toContain('Mira Muster')
        ->toContain('Ich baue gerne Dinge.')
        ->and($zip->getFromName('attachments/Lebenslauf.pdf'))->toBe('pdf-content');

    $zip->close();
})->group('applications');

it('exports an application to a local directory when requested', function () {
    Storage::fake('s3');

    $application = Application::factory()->submitted()->create([
        'first_name' => 'Mira',
        'last_name' => 'Muster',
    ]);

    $dir = sys_get_temp_dir().'/application-export-'.uniqid();

    runArtisan('applications:export', ['application' => $application->id, '--dir' => $dir])
        ->assertSuccessful();

    $zipPath = "{$dir}/bewerbung-{$application->id}-mira-muster.zip";

    expect(file_exists($zipPath))->toBeTrue();

    $zip = new ZipArchive;
    $zip->open($zipPath);

    expect($zip->getFromName('bewerbung.md'))->toContain('Mira Muster');

    $zip->close();
    File::deleteDirectory($dir);
})->group('applications');

it('fails for an unknown application', function () {
    runArtisan('applications:export', ['application' => 999])->assertFailed();
})->group('applications');
