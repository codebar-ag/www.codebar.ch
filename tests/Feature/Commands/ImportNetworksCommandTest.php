<?php

declare(strict_types=1);

use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files  filename => file contents
 */
function writeNetworkFiles(array $files): string
{
    $base = TempDirectories::next('networks-import');
    File::ensureDirectoryExists($base);

    foreach ($files as $filename => $contents) {
        File::put($base.'/'.$filename, $contents);
    }

    return $base;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('imports every network partner from the real files', function () {
    runArtisan('networks:import')->assertExitCode(0);

    expect(Network::count())->toBe(count(File::files(database_path('files/networks'))));

    $network = Network::where('key', 'docuware')->firstOrFail();

    expect($network->getTranslation('name', 'de_CH', false))->toBe('DocuWare')
        ->and($network->getTranslation('excerpt', 'en_CH', false))->toBe('DMS/ECM')
        ->and($network->category)->toBe(NetworkCategoryEnum::SOFTWARE)
        ->and($network->status)->toBe(NetworkStatusEnum::ACTIVE)
        ->and($network->sort)->toBe(30)
        ->and($network->website)->toBe('https://start.docuware.com')
        ->and($network->published)->toBeTrue();
})->group('network', 'console');

it('can be run repeatedly without creating duplicates', function () {
    runArtisan('networks:import')->assertExitCode(0);
    runArtisan('networks:import')->assertExitCode(0);

    expect(Network::where('key', 'docuware')->count())->toBe(1);
})->group('network', 'console');

it('writes nothing on a dry run', function () {
    runArtisan('networks:import', ['--dry-run' => true])->assertExitCode(0);

    expect(Network::count())->toBe(0);
})->group('network', 'console');

it('removes a network whose file is gone', function () {
    Network::factory()->create(['key' => 'orphan-network']);

    runArtisan('networks:import')->assertExitCode(0);

    expect(Network::where('key', 'orphan-network')->exists())->toBeFalse();
})->group('network', 'console');

it('skips a network missing a localised name and fails', function () {
    $base = writeNetworkFiles([
        'halb.yaml' => "key: halb\ncategory: software\nname:\n  de_CH: 'Nur Deutsch'\n",
    ]);

    runArtisan('networks:import', ['--path' => $base])
        ->expectsOutputToContain('halb.yaml is missing a name for en_CH.')
        ->assertExitCode(1);

    expect(Network::where('key', 'halb')->exists())->toBeFalse();
})->group('network', 'console');

it('skips a network without a valid category and fails', function () {
    $base = writeNetworkFiles([
        'bogus.yaml' => "key: bogus\ncategory: bogus\nname:\n  de_CH: Bogus\n  en_CH: Bogus\n",
    ]);

    runArtisan('networks:import', ['--path' => $base])->assertExitCode(1);

    expect(Network::count())->toBe(0);
})->group('network', 'console');
