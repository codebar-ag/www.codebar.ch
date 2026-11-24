<?php

declare(strict_types=1);

use App\Enums\InfrastructureOperationEnum;
use App\Models\InfrastructureConnection;
use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use App\Models\InfrastructureStack;
use Database\Seeders\InfrastructureSeeder;

use function Pest\Laravel\seed;

it('seeds layers, services, connections and the default stack', function () {
    seed(InfrastructureSeeder::class);

    $default = InfrastructureStack::default();

    expect(InfrastructureLayer::query()->orderBy('order')->pluck('key')->all())->toBe(['network', 'platform', 'services', 'ai', 'monitoring', 'development', 'backups'])
        ->and(InfrastructureService::query()->count())->toBe(24)
        ->and(InfrastructureConnection::query()->count())->toBe(26)
        ->and(InfrastructureStack::query()->count())->toBe(1)
        ->and($default?->slug)->toBe('web.codebar')
        ->and($default?->domain)->toBe('www.codebar.ch')
        ->and($default?->services)->toContain('gitlab', 'synon', 'synoff')
        ->and($default?->overrides)->toBe(['cache' => ['provider' => 'Neon']]);
})->group('infrastructure');

it('can run twice without duplicates and restores changed rows', function () {
    seed(InfrastructureSeeder::class);

    InfrastructureService::query()->where('key', 'domain')->update(['provider' => 'Changed']);
    InfrastructureLayer::factory()->create(['key' => 'obsolete']);
    InfrastructureService::factory()->create(['key' => 'obsolete']);
    InfrastructureConnection::query()->create(['from' => 'x', 'to' => 'y']);

    seed(InfrastructureSeeder::class);

    expect(InfrastructureLayer::query()->count())->toBe(7)
        ->and(InfrastructureService::query()->count())->toBe(24)
        ->and(InfrastructureConnection::query()->count())->toBe(26)
        ->and(InfrastructureStack::query()->count())->toBe(1)
        ->and(InfrastructureService::query()->where('key', 'domain')->value('provider'))->toBe('Hosttech');
})->group('infrastructure');

it('keeps customer stacks and leaves only one default', function () {
    InfrastructureStack::factory()->create(['slug' => 'web.customer', 'is_default' => true]);

    seed(InfrastructureSeeder::class);

    expect(InfrastructureStack::query()->where('is_default', true)->pluck('slug')->all())->toBe(['web.codebar'])
        ->and(InfrastructureStack::query()->where('slug', 'web.customer')->exists())->toBeTrue();
})->group('infrastructure');

it('seeds complete and consistent data', function () {
    seed(InfrastructureSeeder::class);

    /** @var list<string> $keys */
    $keys = InfrastructureService::query()->pluck('key')->all();

    foreach (InfrastructureService::query()->get() as $service) {
        foreach (['de_CH', 'en_CH'] as $locale) {
            /** @var string $name */
            $name = $service->getTranslation('name', $locale);
            /** @var string $text */
            $text = $service->getTranslation('text', $locale);

            expect($name)->not->toBeEmpty()
                ->and($text)->not->toBeEmpty()
                ->and($text)->not->toContain('ß');
        }

        expect($service->provider)->not->toBeEmpty()
            ->and($service->operation)->toBeInstanceOf(InfrastructureOperationEnum::class)
            ->and($service->countries)->not->toBeEmpty();

        foreach ($service->countries as $code) {
            expect(__('components.infrastructure.countries.'.$code))->not->toStartWith('components.');
        }

        foreach ($service->links as $link) {
            expect($link['url'])->toStartWith('https://')
                ->and(__('components.infrastructure.link_'.$link['type']))->not->toStartWith('components.');
        }
    }

    foreach (InfrastructureLayer::query()->get() as $layer) {
        expect($layer->color)->toMatch('/^#[0-9A-F]{6}$/')
            ->and($layer->name)->not->toBeEmpty();
    }

    foreach (InfrastructureConnection::query()->get() as $connection) {
        expect(in_array($connection->from, [...$keys, InfrastructureConnection::REQUEST], true))->toBeTrue()
            ->and(in_array($connection->to, $keys, true))->toBeTrue();
    }

    expect(array_diff(InfrastructureStack::query()->where('is_default', true)->sole()->services, $keys))->toBe([]);
})->group('infrastructure');
