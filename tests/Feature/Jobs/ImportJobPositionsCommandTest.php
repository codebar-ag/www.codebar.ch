<?php

declare(strict_types=1);

use App\Enums\JobPositionStatusEnum;
use App\Models\JobPosition;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files  filename => yaml
 */
function writeJobPositionFiles(array $files): string
{
    $base = TempDirectories::next('jobs-import');
    File::ensureDirectoryExists($base);

    foreach ($files as $name => $contents) {
        File::put($base.'/'.$name, $contents);
    }

    return $base;
}

function jobPositionYaml(string $key, string $status = 'open', bool $published = true): string
{
    $flag = $published ? 'true' : 'false';

    return <<<YAML
        key: {$key}
        published: {$flag}
        sort: 1
        status: {$status}
        route_name: jobs.internship.show
        title:
          de_CH: 'Praktikum DE'
          en_CH: 'Internship EN'
        teaser:
          de_CH: 'Teaser DE'
          en_CH: 'Teaser EN'
        YAML;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('imports a position from a yaml file', function () {
    $base = writeJobPositionFiles(['test-position.yaml' => jobPositionYaml('test-position', 'in-process')]);

    runArtisan('jobs:import', ['--path' => $base])->assertExitCode(0);

    $position = JobPosition::where('key', 'test-position')->firstOrFail();

    expect($position->published)->toBeTrue()
        ->and($position->status)->toBe(JobPositionStatusEnum::InProcess)
        ->and($position->route_name)->toBe('jobs.internship.show')
        ->and($position->getTranslation('title', 'de_CH'))->toBe('Praktikum DE')
        ->and($position->getTranslation('teaser', 'en_CH'))->toBe('Teaser EN');
})->group('applications');

it('removes a position whose file has disappeared', function () {
    JobPosition::factory()->create(['key' => 'gone']);

    $base = writeJobPositionFiles(['test-position.yaml' => jobPositionYaml('test-position')]);

    runArtisan('jobs:import', ['--path' => $base])->assertExitCode(0);

    expect(JobPosition::where('key', 'gone')->exists())->toBeFalse()
        ->and(JobPosition::where('key', 'test-position')->exists())->toBeTrue();
})->group('applications');

it('skips a position with an unknown status', function () {
    $base = writeJobPositionFiles(['test-position.yaml' => jobPositionYaml('test-position', 'filled')]);

    runArtisan('jobs:import', ['--path' => $base])->assertExitCode(1);

    expect(JobPosition::where('key', 'test-position')->exists())->toBeFalse();
})->group('applications');

it('skips a position missing a title language', function () {
    $yaml = <<<'YAML'
        key: test-position
        published: true
        status: open
        title:
          de_CH: 'Nur Deutsch'
        YAML;

    $base = writeJobPositionFiles(['test-position.yaml' => $yaml]);

    runArtisan('jobs:import', ['--path' => $base])->assertExitCode(1);

    expect(JobPosition::where('key', 'test-position')->exists())->toBeFalse();
})->group('applications');

it('imports the real content files', function () {
    runArtisan('jobs:import')->assertExitCode(0);

    expect(JobPosition::where('key', 'praktikum-ims')->exists())->toBeTrue();
})->group('applications');
