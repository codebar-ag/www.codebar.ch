<?php

declare(strict_types=1);

use App\Models\Technology;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files  locale => file contents
 */
function writeTechnologyFiles(string $key, array $files): string
{
    $base = TempDirectories::next('technologies-import');

    foreach ($files as $locale => $contents) {
        File::ensureDirectoryExists($base.'/'.$locale);
        File::put($base.'/'.$locale.'/'.$key.'.md', $contents);
    }

    return $base;
}

function technologyFile(string $key, string $title): string
{
    return <<<MD
        ---
        key: {$key}
        title: {$title}
        group: Backend
        order: 1
        tags: [Test]
        ---

        Etwas Fliesstext.
        MD;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('imports every technology from the real files', function () {
    runArtisan('technologies:import')->assertExitCode(0);

    expect(Technology::count())->toBe(count(File::files(database_path('files/technologies/de_CH'))));

    $technology = Technology::where('slug', 'laravel-framework')->firstOrFail();

    expect($technology->getTranslation('title', 'de_CH'))->toBe('Laravel')
        ->and($technology->getTranslation('title', 'en_CH'))->toBe('Laravel')
        ->and($technology->group)->toBe('Backend')
        ->and($technology->order)->toBe(1)
        ->and($technology->tags)->toBe(['PHP'])
        ->and($technology->link)->toBe('https://laravel.com/')
        ->and($technology->published)->toBeTrue();
})->group('technologies', 'console');

it('can be run repeatedly without creating duplicates', function () {
    runArtisan('technologies:import')->assertExitCode(0);
    runArtisan('technologies:import')->assertExitCode(0);

    expect(Technology::where('slug', 'laravel-framework')->count())->toBe(1);
})->group('technologies', 'console');

it('writes nothing on a dry run', function () {
    runArtisan('technologies:import', ['--dry-run' => true])->assertExitCode(0);

    expect(Technology::count())->toBe(0);
})->group('technologies', 'console');

it('removes a technology whose files are gone', function () {
    Technology::factory()->create(['slug' => 'orphan-technology']);

    runArtisan('technologies:import')->assertExitCode(0);

    expect(Technology::where('slug', 'orphan-technology')->exists())->toBeFalse();
})->group('technologies', 'console');

it('skips a technology missing a translation and fails', function () {
    $base = writeTechnologyFiles('halbe-technologie', [
        'de_CH' => technologyFile('halbe-technologie', 'Nur Deutsch'),
    ]);

    runArtisan('technologies:import', ['--path' => $base])
        ->expectsOutputToContain('"halbe-technologie" is missing a translation for en_CH')
        ->assertExitCode(1);

    expect(Technology::where('slug', 'halbe-technologie')->exists())->toBeFalse();
})->group('technologies', 'console');
