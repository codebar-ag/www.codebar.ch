<?php

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Security\Policies\NewsPolicy;
use App\Security\Policies\ProductPolicy;
use App\Security\Policies\ServicePolicy;

it('denies every ability on the NewsPolicy', function () {
    $policy = new NewsPolicy;
    $user = User::factory()->make();
    $news = News::factory()->make();

    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $news))->toBeFalse();
    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $news))->toBeFalse();
    expect($policy->delete($user, $news))->toBeFalse();
    expect($policy->restore($user, $news))->toBeFalse();
    expect($policy->forceDelete($user, $news))->toBeFalse();
})->group('unit', 'policies');

it('denies every ability on the ProductPolicy', function () {
    $policy = new ProductPolicy;
    $user = User::factory()->make();
    $product = Product::factory()->make();

    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $product))->toBeFalse();
    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $product))->toBeFalse();
    expect($policy->delete($user, $product))->toBeFalse();
    expect($policy->restore($user, $product))->toBeFalse();
    expect($policy->forceDelete($user, $product))->toBeFalse();
})->group('unit', 'policies');

it('denies every ability on the ServicePolicy', function () {
    $policy = new ServicePolicy;
    $user = User::factory()->make();
    $service = Service::factory()->make();

    expect($policy->viewAny($user))->toBeFalse();
    expect($policy->view($user, $service))->toBeFalse();
    expect($policy->create($user))->toBeFalse();
    expect($policy->update($user, $service))->toBeFalse();
    expect($policy->delete($user, $service))->toBeFalse();
    expect($policy->restore($user, $service))->toBeFalse();
    expect($policy->forceDelete($user, $service))->toBeFalse();
})->group('unit', 'policies');
