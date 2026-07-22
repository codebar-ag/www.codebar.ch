<?php

use App\Enums\NetworkStatusEnum;

it('returns correct labels array', function () {
    expect(NetworkStatusEnum::ACTIVE->getLabel())->toBe(__('Active'))
        ->and(NetworkStatusEnum::ENDED->getLabel())->toBe(__('Ended'));
})->group('enums', 'network-status-enum');

it('returns correct cases array', function () {

    $expectedCases = [
        NetworkStatusEnum::ACTIVE,
        NetworkStatusEnum::ENDED,
    ];

    expect(NetworkStatusEnum::cases())->toBe($expectedCases);

})->group('enums', 'network-status-enum');
