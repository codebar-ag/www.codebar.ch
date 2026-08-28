<?php

declare(strict_types=1);

use App\Console\Commands\ImportCommand;
use App\Console\Commands\ImportTechnologiesCommand;
use App\Models\Technology;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

function writeMisnamedTechnologyFixture(string $filename, string $key): string
{
    $base = TempDirectories::next('import-command');

    $contents = <<<MD
        ---
        key: {$key}
        title: Titel
        group: Backend
        ---

        Etwas Fliesstext.
        MD;

    foreach (['de_CH', 'en_CH'] as $locale) {
        File::ensureDirectoryExists($base.'/'.$locale);
        File::put($base.'/'.$locale.'/'.$filename, $contents);
    }

    return $base;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('warns about a file that does not match its key but imports it anyway', function () {
    $base = writeMisnamedTechnologyFixture('misnamed.md', 'proper-key');

    runArtisan('technologies:import', ['--path' => $base])
        ->expectsOutputToContain('misnamed.md should be named proper-key.md.')
        ->assertExitCode(0);

    expect(Technology::where('slug', 'proper-key')->exists())->toBeTrue();
})->group('console');

it('stays silent when the file name matches its key', function () {
    $base = writeMisnamedTechnologyFixture('proper-key.md', 'proper-key');

    runArtisan('technologies:import', ['--path' => $base])
        ->doesntExpectOutputToContain('should be named')
        ->assertExitCode(0);

    expect(Technology::where('slug', 'proper-key')->exists())->toBeTrue();
})->group('console');

it('reports exactly the locales a record is missing', function (array $present, array $expected) {
    $method = new ReflectionMethod(ImportCommand::class, 'missingLocales');

    expect($method->invoke(app(ImportTechnologiesCommand::class), ['de_CH', 'en_CH'], $present))
        ->toBe($expected);
})->with([
    'both present' => [['de_CH' => 'a', 'en_CH' => 'b'], []],
    'english missing' => [['de_CH' => 'a'], ['en_CH']],
    'german missing' => [['en_CH' => 'b'], ['de_CH']],
    'both missing' => [[], ['de_CH', 'en_CH']],
])->group('console');
