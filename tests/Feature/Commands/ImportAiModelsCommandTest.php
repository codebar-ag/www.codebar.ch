<?php

declare(strict_types=1);

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use Illuminate\Support\Facades\File;
use Tests\Support\TempDirectories;

/**
 * @param  array<string, string>  $files  filename => file contents
 */
function writeAiModelFiles(array $files): string
{
    $base = TempDirectories::next('ai-models-import');
    File::ensureDirectoryExists($base);

    foreach ($files as $filename => $contents) {
        File::put($base.'/'.$filename, $contents);
    }

    return $base;
}

afterEach(function () {
    TempDirectories::cleanUp();
});

it('imports every model from the real files', function () {
    runArtisan('ai-models:import')->assertExitCode(0);

    expect(AiModel::count())->toBe(count(File::files(database_path('files/ai_models'))));

    $model = AiModel::where('name', 'deepseek-ocr')->firstOrFail();

    expect($model->category)->toBe(AiModelCategoryEnum::VISION_DOCUMENTS)
        ->and($model->order)->toBe(4)
        ->and($model->provider)->toBe('DeepSeek AI (CN)')
        ->and($model->license)->toBe('MIT')
        ->and($model->role)->toBe(['de_CH' => 'PDF/Scan → Markdown', 'en_CH' => 'PDF/scan → Markdown'])
        ->and($model->link_url)->toBe('https://ollama.com/library/deepseek-ocr');
})->group('ai', 'console');

it('resolves replaced_by references by key in a second pass', function () {
    runArtisan('ai-models:import')->assertExitCode(0);

    $archived = AiModel::where('name', 'gemma4:12b')->firstOrFail();
    $successor = AiModel::where('name', 'qwen3-vl:8b')->firstOrFail();

    expect($archived->replaced_by_id)->toBe($successor->id)
        ->and($archived->archived_at?->toDateString())->toBe('2026-07-01');
})->group('ai', 'console');

it('can be run repeatedly without creating duplicates', function () {
    runArtisan('ai-models:import')->assertExitCode(0);
    runArtisan('ai-models:import')->assertExitCode(0);

    expect(AiModel::where('name', 'deepseek-ocr')->count())->toBe(1);
})->group('ai', 'console');

it('writes nothing on a dry run', function () {
    runArtisan('ai-models:import', ['--dry-run' => true])->assertExitCode(0);

    expect(AiModel::count())->toBe(0);
})->group('ai', 'console');

it('removes a model whose file is gone', function () {
    AiModel::create([
        'name' => 'orphan-model',
        'category' => AiModelCategoryEnum::REASONING_CODING->value,
        'order' => 99,
    ]);

    runArtisan('ai-models:import')->assertExitCode(0);

    expect(AiModel::where('name', 'orphan-model')->exists())->toBeFalse();
})->group('ai', 'console');

it('reports an unknown replaced_by key without failing the import', function () {
    $base = writeAiModelFiles([
        'lonely.yaml' => "key: lonely\nname: lonely\ncategory: reasoning_coding\nreplaced_by: nonexistent\n",
    ]);

    runArtisan('ai-models:import', ['--path' => $base])
        ->expectsOutputToContain('"lonely" replaces an unknown model key "nonexistent".')
        ->assertExitCode(0);

    expect(AiModel::where('name', 'lonely')->firstOrFail()->replaced_by_id)->toBeNull();
})->group('ai', 'console');

it('warns when a file breaks the naming convention', function () {
    $base = writeAiModelFiles([
        'wrong.yaml' => "key: right\nname: right\ncategory: reasoning_coding\n",
    ]);

    runArtisan('ai-models:import', ['--path' => $base])
        ->expectsOutputToContain('wrong.yaml should be named right.yaml.')
        ->assertExitCode(0);

    expect(AiModel::where('name', 'right')->exists())->toBeTrue();
})->group('ai', 'console');

it('skips a model without a valid category and fails', function () {
    $base = writeAiModelFiles([
        'bogus.yaml' => "key: bogus\nname: bogus\ncategory: bogus\n",
    ]);

    runArtisan('ai-models:import', ['--path' => $base])->assertExitCode(1);

    expect(AiModel::count())->toBe(0);
})->group('ai', 'console');
