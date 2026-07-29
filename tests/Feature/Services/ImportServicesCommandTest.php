<?php

declare(strict_types=1);

use App\Models\Service;
use Illuminate\Support\Facades\File;

/**
 * Keeps track of the throwaway directories a test wrote to, so afterEach can
 * remove them without leaning on undeclared $this properties.
 */
class ServiceTempDirectories
{
    /** @var array<int, string> */
    private static array $paths = [];

    public static function next(): string
    {
        $path = sys_get_temp_dir().'/services-import-'.bin2hex(random_bytes(4));
        self::$paths[] = $path;

        return $path;
    }

    public static function cleanUp(): void
    {
        foreach (self::$paths as $path) {
            File::deleteDirectory($path);
        }

        self::$paths = [];
    }
}

/**
 * @param  array<string, string>  $files  locale => file contents
 */
function writeServiceFiles(string $key, array $files): string
{
    $base = ServiceTempDirectories::next();

    foreach ($files as $locale => $contents) {
        File::ensureDirectoryExists($base.'/'.$locale);
        File::put($base.'/'.$locale.'/'.$key.'.md', $contents);
    }

    return $base;
}

function serviceFile(string $key, string $name, string $extra = ''): string
{
    return <<<MD
        ---
        key: {$key}
        name: {$name}
        teaser: Ein Teaser.
        {$extra}
        ---

        ## Ein Abschnitt

        Etwas Fliesstext.
        MD;
}

afterEach(function () {
    ServiceTempDirectories::cleanUp();
});

it('imports a service that exists in both languages', function () {
    $base = writeServiceFiles('test-service', [
        'de_CH' => serviceFile('test-service', 'Testleistung', "order: 1\npublished: true\ntags: [Test]"),
        'en_CH' => serviceFile('test-service', 'Test service'),
    ]);

    runArtisan('services:import', ['--path' => $base])->assertExitCode(0);

    $service = Service::where('slug', 'test-service')->firstOrFail();

    expect($service->getTranslation('name', 'de_CH', false))->toBe('Testleistung');
    expect($service->getTranslation('name', 'en_CH', false))->toBe('Test service');
    expect($service->published)->toBeTrue();
    expect($service->order)->toBe(1);
    expect($service->tags)->toBe(['Test']);
})->group('services', 'console');

it('reports a service missing a translation instead of importing it', function () {
    $base = writeServiceFiles('test-service', [
        'de_CH' => serviceFile('test-service', 'Testleistung'),
    ]);

    runArtisan('services:import', ['--path' => $base])->assertExitCode(1);

    expect(Service::where('slug', 'test-service')->exists())->toBeFalse();
})->group('services', 'console');

it('can be run repeatedly without creating duplicates', function () {
    $base = writeServiceFiles('test-service', [
        'de_CH' => serviceFile('test-service', 'Testleistung'),
        'en_CH' => serviceFile('test-service', 'Test service'),
    ]);

    runArtisan('services:import', ['--path' => $base])->assertExitCode(0);
    runArtisan('services:import', ['--path' => $base])->assertExitCode(0);

    expect(Service::where('slug', 'test-service')->count())->toBe(1);
})->group('services', 'console');

it('removes a service whose files are gone', function () {
    $base = writeServiceFiles('stays', [
        'de_CH' => serviceFile('stays', 'Bleibt'),
        'en_CH' => serviceFile('stays', 'Stays'),
    ]);
    File::put($base.'/de_CH/leaves.md', serviceFile('leaves', 'Geht'));
    File::put($base.'/en_CH/leaves.md', serviceFile('leaves', 'Leaves'));

    runArtisan('services:import', ['--path' => $base])->assertExitCode(0);
    expect(Service::count())->toBe(2);

    File::delete($base.'/de_CH/leaves.md');
    File::delete($base.'/en_CH/leaves.md');

    runArtisan('services:import', ['--path' => $base])->assertExitCode(0);

    expect(Service::count())->toBe(1);
    expect(Service::where('slug', 'leaves')->exists())->toBeFalse();
})->group('services', 'console');

it('writes nothing on a dry run', function () {
    $base = writeServiceFiles('test-service', [
        'de_CH' => serviceFile('test-service', 'Testleistung'),
        'en_CH' => serviceFile('test-service', 'Test service'),
    ]);

    runArtisan('services:import', ['--path' => $base, '--dry-run' => true])->assertExitCode(0);

    expect(Service::where('slug', 'test-service')->exists())->toBeFalse();
})->group('services', 'console');
