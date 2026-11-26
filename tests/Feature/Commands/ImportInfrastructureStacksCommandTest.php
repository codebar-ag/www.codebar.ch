<?php

declare(strict_types=1);

use App\Models\InfrastructureStack;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files
 */
function writeStackFiles(array $files): string
{
    $base = TempDirectories::next('infrastructure-stacks');
    File::ensureDirectoryExists($base);

    foreach ($files as $filename => $contents) {
        File::put($base.'/'.$filename, $contents);
    }

    return $base;
}

it('imports a stack with its sketch and updates it on the next run', function () {
    $path = writeStackFiles([
        'web.example.json' => (string) json_encode(['name' => 'Example', 'domain' => 'www.example.ch', 'stations' => ['domain', 7, 'app'], 'overrides' => ['app' => ['provider' => 'Forge'], 3 => ['x' => 1], 'db' => 'no']]),
        'web.example.svg' => '<svg/>',
    ]);

    runArtisan('infrastructure:import-stacks', ['path' => $path])->expectsOutputToContain('1 stacks imported.')->assertSuccessful();

    $stack = InfrastructureStack::query()->where('slug', 'web.example')->sole();

    expect($stack->name)->toBe('Example')
        ->and($stack->domain)->toBe('www.example.ch')
        ->and($stack->sketch)->toBe('<svg/>')
        ->and($stack->services)->toBe(['domain', 'app'])
        ->and($stack->overrides)->toBe(['app' => ['provider' => 'Forge']])
        ->and($stack->is_default)->toBeFalse();

    File::put($path.'/web.example.webp', 'photo');

    runArtisan('infrastructure:import-stacks', ['path' => $path])->assertSuccessful();

    expect($stack->refresh()->sketch)->toBe('data:image/webp;base64,'.base64_encode('photo'));

    File::delete($path.'/web.example.webp');
    File::put($path.'/web.example.json', (string) json_encode(['stations' => ['db']]));
    File::delete($path.'/web.example.svg');

    runArtisan('infrastructure:import-stacks', ['path' => $path.'/'])->assertSuccessful();

    $stack->refresh();

    expect(InfrastructureStack::query()->count())->toBe(1)
        ->and($stack->name)->toBe('web.example')
        ->and($stack->domain)->toBe('web.example')
        ->and($stack->sketch)->toBeNull()
        ->and($stack->services)->toBe(['db'])
        ->and($stack->overrides)->toBe([]);
})->group('infrastructure');

it('skips files that are not a usable stack', function (string $filename, string $contents) {
    $path = writeStackFiles([$filename => $contents]);

    runArtisan('infrastructure:import-stacks', ['path' => $path])
        ->expectsOutputToContain('Skipped '.$filename)
        ->expectsOutputToContain('0 stacks imported.')
        ->assertSuccessful();

    expect(InfrastructureStack::query()->count())->toBe(0);
})->with([
    'broken json' => ['web.example.json', '{'],
    'not an object' => ['web.example.json', '"text"'],
    'no stations' => ['web.example.json', '{"name":"x"}'],
    'stations not a list' => ['web.example.json', '{"stations":"app"}'],
    'empty file' => ['web.example.json', ''],
    'invalid slug' => ['Web Example.json', '{"stations":[]}'],
    'helper file' => ['web.example.cta.json', '{"x":1}'],
])->group('infrastructure');

it('never overwrites the default stack', function () {
    InfrastructureStack::factory()->create(['slug' => 'web.codebar', 'is_default' => true, 'services' => ['app'], 'domain' => 'www.codebar.ch']);
    $path = writeStackFiles(['web.codebar.json' => '{"stations":["db"],"domain":"other.ch"}']);

    runArtisan('infrastructure:import-stacks', ['path' => $path])->expectsOutputToContain('Skipped web.codebar.json')->assertSuccessful();

    expect(InfrastructureStack::default()?->services)->toBe(['app'])
        ->and(InfrastructureStack::default()?->domain)->toBe('www.codebar.ch');
})->group('infrastructure');

it('ignores other files and reports an empty folder', function () {
    $path = writeStackFiles(['notes.txt' => 'x', 'web.example.svg' => '<svg/>']);

    runArtisan('infrastructure:import-stacks', ['path' => $path])->expectsOutputToContain('0 stacks imported.')->assertSuccessful();
})->group('infrastructure');

it('fails for a missing folder', function () {
    runArtisan('infrastructure:import-stacks', ['path' => '/nonexistent/infrastructure'])
        ->expectsOutputToContain('Folder not found')
        ->assertFailed();
})->group('infrastructure');

it('reads the configured folder when no path is given', function () {
    $path = writeStackFiles(['web.example.json' => '{"stations":["app"]}']);
    config()->set('infrastructure.customers_path', $path);

    runArtisan('infrastructure:import-stacks')->expectsOutputToContain('1 stacks imported.')->assertSuccessful();

    expect(InfrastructureStack::findBySlug('web.example'))->not->toBeNull();
})->group('infrastructure');
