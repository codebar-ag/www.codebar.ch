<?php

declare(strict_types=1);

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files  locale => file contents
 */
function writeProductFiles(string $key, array $files): string
{
    $base = TempDirectories::next('products-import');

    foreach ($files as $locale => $contents) {
        File::ensureDirectoryExists($base.'/'.$locale);
        File::put($base.'/'.$locale.'/'.$key.'.md', $contents);
    }

    return $base;
}

function productFile(string $key, string $name): string
{
    return <<<MD
        ---
        key: {$key}
        name: {$name}
        order: 1
        headline: Eine Headline.
        teaser: Ein Teaser.
        ---

        Etwas Fliesstext.
        MD;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('imports every product from the real files', function () {
    runArtisan('products:import')->assertExitCode(0);

    expect(Product::count())->toBe(count(File::files(database_path('files/products/de_CH'))));

    $product = Product::where('slug', 'flows')->firstOrFail();

    expect($product->getTranslation('name', 'de_CH'))->toBe('Flows')
        ->and($product->getTranslation('name', 'en_CH'))->toBe('Flows')
        ->and($product->getTranslation('headline', 'en_CH'))->toBe('Document workflows, run by agents. On infrastructure you control.')
        ->and($product->getTranslation('content', 'de_CH'))->not->toBeEmpty()
        ->and($product->order)->toBe(1)
        ->and($product->published)->toBeTrue();
})->group('products', 'console');

it('can be run repeatedly without creating duplicates', function () {
    runArtisan('products:import')->assertExitCode(0);
    runArtisan('products:import')->assertExitCode(0);

    expect(Product::where('slug', 'flows')->count())->toBe(1);
})->group('products', 'console');

it('writes nothing on a dry run', function () {
    runArtisan('products:import', ['--dry-run' => true])->assertExitCode(0);

    expect(Product::count())->toBe(0);
})->group('products', 'console');

it('removes a product whose files are gone', function () {
    Product::factory()->create(['slug' => 'orphan-product']);

    runArtisan('products:import')->assertExitCode(0);

    expect(Product::where('slug', 'orphan-product')->exists())->toBeFalse();
})->group('products', 'console');

it('skips a product missing a translation and fails', function () {
    $base = writeProductFiles('halbes-produkt', [
        'de_CH' => productFile('halbes-produkt', 'Nur Deutsch'),
    ]);

    runArtisan('products:import', ['--path' => $base])
        ->expectsOutputToContain('"halbes-produkt" is missing a translation for en_CH')
        ->assertExitCode(1);

    expect(Product::where('slug', 'halbes-produkt')->exists())->toBeFalse();
})->group('products', 'console');
