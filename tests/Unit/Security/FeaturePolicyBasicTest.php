<?php

declare(strict_types=1);

use App\Security\FeaturePolicyBasic;

it('configures geolocation and lets the article video player go fullscreen', function () {
    $policy = new FeaturePolicyBasic;
    $policy->configure();

    $header = (string) $policy;

    expect($header)->toContain('geolocation=(self)')
        ->and($header)->toContain('fullscreen=(self "https://player.vimeo.com")');
})->group('security');
