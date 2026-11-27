<?php

declare(strict_types=1);

use App\Enums\InfrastructureOperationEnum;
use App\Models\InfrastructureConnection;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use App\Models\InfrastructureStack;
use Illuminate\Database\QueryException;

it('orders the services of a layer and deletes them with the layer', function () {
    $layer = InfrastructureLayer::factory()->create();
    InfrastructureService::factory()->for($layer, 'layer')->create(['key' => 'second', 'order' => 2]);
    InfrastructureService::factory()->for($layer, 'layer')->create(['key' => 'first', 'order' => 1]);

    expect($layer->services()->pluck('key')->all())->toBe(['first', 'second'])
        ->and(InfrastructureService::query()->where('key', 'first')->sole()->layer()->is($layer))->toBeTrue();

    $layer->delete();

    expect(InfrastructureService::query()->count())->toBe(0);
})->group('unit', 'infrastructure');

it('translates the layer name and the service name and text', function () {
    $layer = InfrastructureLayer::factory()->create(['name' => ['de_CH' => 'Netzwerk', 'en_CH' => 'Network']]);
    $service = InfrastructureService::factory()->for($layer, 'layer')->create([
        'name' => ['de_CH' => 'Dateien', 'en_CH' => 'Files'],
        'text' => ['de_CH' => 'Deutsch.', 'en_CH' => 'English.'],
    ]);

    app()->setLocale('en_CH');

    expect($layer->name)->toBe('Network')
        ->and($service->name)->toBe('Files')
        ->and($service->text)->toBe('English.');

    app()->setLocale('de_CH');

    expect($layer->name)->toBe('Netzwerk')->and($service->name)->toBe('Dateien');
})->group('unit', 'infrastructure');

it('casts countries, links and the operation of a service', function () {
    $service = InfrastructureService::factory()->create(['operation' => 'managed', 'countries' => ['CH', 'DE']])->refresh();

    expect($service->operation)->toBe(InfrastructureOperationEnum::MANAGED)
        ->and($service->countries)->toBe(['CH', 'DE'])
        ->and($service->links)->toBe([['type' => 'website', 'url' => 'https://example.com']]);
})->group('unit', 'infrastructure');

it('labels every operation in both languages', function (InfrastructureOperationEnum $operation) {
    foreach (['de_CH', 'en_CH'] as $locale) {
        app()->setLocale($locale);

        expect($operation->getLabel())->not->toStartWith('components.')
            ->and($operation->getLabel())->not->toBeEmpty();
    }
})->with(InfrastructureOperationEnum::cases())->group('unit', 'infrastructure');

it('rejects a second layer, service, stack or connection with the same key', function (Closure $create) {
    $create();

    expect($create)->toThrow(QueryException::class);
})->with([
    'layer' => [fn () => InfrastructureLayer::factory()->create(['key' => 'same'])],
    'service' => [fn () => InfrastructureService::factory()->create(['key' => 'same'])],
    'stack' => [fn () => InfrastructureStack::factory()->create(['slug' => 'web.same'])],
    'connection' => [fn () => InfrastructureConnection::query()->create(['from' => 'a', 'to' => 'b'])],
])->group('unit', 'infrastructure');

it('finds the default stack and prefers the oldest when several are marked', function () {
    expect(InfrastructureStack::default())->toBeNull();

    InfrastructureStack::factory()->create(['slug' => 'web.customer']);
    $first = InfrastructureStack::factory()->create(['slug' => 'web.first', 'is_default' => true]);
    InfrastructureStack::factory()->create(['slug' => 'web.second', 'is_default' => true]);

    expect(InfrastructureStack::default()?->is($first))->toBeTrue();
})->group('unit', 'infrastructure');

it('finds a stack by a valid slug only', function (string $slug, bool $found) {
    InfrastructureStack::factory()->create(['slug' => 'web.example']);
    InfrastructureStack::factory()->create(['slug' => 'ab']);
    InfrastructureStack::factory()->create(['slug' => str_repeat('a', 61)]);

    expect(InfrastructureStack::findBySite($slug) !== null)->toBe($found);
})->with([
    ['web.example', true],
    ['ab', true],
    [str_repeat('a', 61), true],
    [str_repeat('a', 62), false],
    ['a', false],
    ['', false],
    ['WEB.EXAMPLE', false],
    ['web.example ', false],
    ["web.example\n", false],
    ['.web.example', false],
    ['-web', false],
    ['web/example', false],
    ['../web.example', false],
    ['web.missing', false],
    ["web.example' or 1=1", false],
])->group('unit', 'infrastructure');

it('uses the sketch as cover, then the image, then nothing', function () {
    $stack = InfrastructureStack::factory()->make(['sketch' => '<svg/>', 'image' => 'images/a.svg']);

    expect($stack->cover())->toBe('data:image/svg+xml;base64,'.base64_encode('<svg/>'));

    $stack->sketch = 'data:image/webp;base64,AAAA';
    expect($stack->cover())->toBe('data:image/webp;base64,AAAA');

    $stack->sketch = '';
    expect($stack->cover())->toBe(asset('images/a.svg'));

    $stack->sketch = null;
    $stack->image = '';
    expect($stack->cover())->toBeNull();

    $stack->image = null;
    expect($stack->cover())->toBeNull();
})->group('unit', 'infrastructure');

it('finds a stack by its domain and prefers a matching slug', function () {
    $byDomain = InfrastructureStack::factory()->create(['slug' => 'web.example', 'domain' => 'www.example.ch']);
    $bySlug = InfrastructureStack::factory()->create(['slug' => 'www.example.ch', 'domain' => 'other.example.ch']);

    expect(InfrastructureStack::findBySite('www.example.ch')?->is($bySlug))->toBeTrue()
        ->and(InfrastructureStack::findBySite('other.example.ch')?->is($bySlug))->toBeTrue()
        ->and(InfrastructureStack::findBySite('web.example')?->is($byDomain))->toBeTrue()
        ->and(InfrastructureStack::findBySite('www.missing.ch'))->toBeNull();
})->group('infrastructure');
