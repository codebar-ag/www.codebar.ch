<?php

declare(strict_types=1);

use App\Helpers\Facades\HelperNumber;

it('helper number: format', function () {
    $number = 1234.56;
    $formatted = HelperNumber::format($number);
    expect($formatted)->toBe("1'234.56");

    $number = null;
    $formatted = HelperNumber::format($number);
    expect($formatted)->toBe('0.00');
})->group('helper', 'number');
