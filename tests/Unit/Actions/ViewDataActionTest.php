<?php

use App\Actions\ViewDataAction;
use App\Enums\ContactSectionEnum;
use App\Models\Configuration;
use App\Models\Contact;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;

it('returns the first configuration', function () {
    $configuration = Configuration::factory()->create();

    $result = (new ViewDataAction)->configuration('de_CH');

    expect($result->is($configuration))->toBeTrue();
})->group('unit', 'actions');

it('only returns published products for the given locale, ordered', function () {
    Product::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 2]);
    Product::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 1]);
    Product::factory()->create(['locale' => 'de_CH', 'published' => false, 'order' => 0]);
    Product::factory()->create(['locale' => 'en_CH', 'published' => true, 'order' => 0]);

    $result = (new ViewDataAction)->products('de_CH');

    expect($result)->toHaveCount(2);
    expect($result->pluck('order')->all())->toBe([1, 2]);
})->group('unit', 'actions');

it('only returns published services for the given locale, ordered', function () {
    Service::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 2]);
    Service::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 1]);
    Service::factory()->create(['locale' => 'de_CH', 'published' => false, 'order' => 0]);

    $result = (new ViewDataAction)->services('de_CH');

    expect($result)->toHaveCount(2);
    expect($result->pluck('order')->all())->toBe([1, 2]);
})->group('unit', 'actions');

it('only returns published news items with a published_at date, ordered descending', function () {
    News::factory()->create(['locale' => 'de_CH', 'published_at' => now()->subDay()]);
    News::factory()->create(['locale' => 'de_CH', 'published_at' => now()]);
    News::factory()->create(['locale' => 'de_CH', 'published_at' => null]);

    $result = (new ViewDataAction)->news('de_CH');

    expect($result)->toHaveCount(2);
    expect($result->first()->published_at->isToday())->toBeTrue();
})->group('unit', 'actions');

it('only returns published technologies for the given locale, ordered', function () {
    Technology::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 2]);
    Technology::factory()->create(['locale' => 'de_CH', 'published' => true, 'order' => 1]);
    Technology::factory()->create(['locale' => 'de_CH', 'published' => false, 'order' => 0]);

    $result = (new ViewDataAction)->technologies('de_CH');

    expect($result)->toHaveCount(2);
    expect($result->pluck('order')->all())->toBe([1, 2]);
})->group('unit', 'actions');

it('only returns published open source projects for the given locale, ordered by downloads', function () {
    OpenSource::factory()->create(['locale' => 'de_CH', 'published' => true, 'downloads' => 10]);
    OpenSource::factory()->create(['locale' => 'de_CH', 'published' => true, 'downloads' => 100]);
    OpenSource::factory()->create(['locale' => 'de_CH', 'published' => false, 'downloads' => 1000]);

    $result = (new ViewDataAction)->openSource('de_CH');

    expect($result)->toHaveCount(2);
    expect($result->first()->downloads)->toBe(100);
})->group('unit', 'actions');

it('groups published contacts by section for the given locale', function () {
    Contact::factory()->create([
        'published' => true,
        'name' => 'Alice',
        'sections' => [
            ContactSectionEnum::EMPLOYEES => ['role' => ['de_CH' => 'Engineer']],
        ],
    ]);

    Contact::factory()->create([
        'published' => false,
        'name' => 'Bob',
        'sections' => [
            ContactSectionEnum::EMPLOYEES => ['role' => ['de_CH' => 'Engineer']],
        ],
    ]);

    $result = (new ViewDataAction)->contacts('de_CH');

    expect($result->{ContactSectionEnum::EMPLOYEES})->toHaveCount(1);
    expect($result->{ContactSectionEnum::EMPLOYEES}->first()->name)->toBe('Alice');
    expect($result->{ContactSectionEnum::COLLABORATIONS})->toHaveCount(0);
})->group('unit', 'actions');
