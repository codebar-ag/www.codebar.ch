<?php

declare(strict_types=1);

use App\Security\Presets\MyCspPreset;
use Spatie\Csp\Policy;

it('configures the expected content security policy directives', function () {
    $policy = new Policy;

    (new MyCspPreset)->configure($policy);

    $contents = $policy->getContents();

    expect($contents)->toContain("base-uri 'self'");
    expect($contents)->toContain("default-src 'self'");
    expect($contents)->toContain("frame-ancestors 'self'");
    expect($contents)->toContain('upgrade-insecure-requests');
    expect($contents)->toContain("object-src 'none'");
    expect($contents)->toContain('img-src');
    expect($contents)->toContain('res.cloudinary.com');
    expect($contents)->toContain('www.gravatar.com');
})->group('unit', 'security');
