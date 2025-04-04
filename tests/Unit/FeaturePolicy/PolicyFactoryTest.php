<?php

use App\Security\FeaturePolicyBasic;
use App\Security\SecurityPolicyBasic;
use Mazedlx\FeaturePolicy\PolicyFactory as FeaturePolicyFactory;
use Spatie\Csp\PolicyFactory as SecurityPolicyFactory;

it('basic policy factory create', function () {
    $policy = FeaturePolicyFactory::create(FeaturePolicyBasic::class);
    expect($policy)->toBeInstanceOf(FeaturePolicyBasic::class);
})
    ->group('unit', 'policies');

it('security header policy factory create', function () {
    $policy = SecurityPolicyFactory::create(SecurityPolicyBasic::class);
    expect($policy)->toBeInstanceOf(SecurityPolicyBasic::class);
})
    ->group('unit', 'policies');
