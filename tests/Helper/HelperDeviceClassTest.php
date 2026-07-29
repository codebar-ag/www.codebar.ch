<?php

declare(strict_types=1);

use App\Helpers\Facades\HelperDevice;

it('helper device: is mobile device', function () {
    $isMobileDevice = HelperDevice::isMobileDevice();
    expect($isMobileDevice)
        ->toBeBool();
})->group('helper', 'device');
