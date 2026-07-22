<?php

use App\Enums\NetworkCategoryEnum;

it('returns correct labels array', function () {
    expect(NetworkCategoryEnum::COLLABORATION->getLabel())->toBe(__('Collaboration Partner'))
        ->and(NetworkCategoryEnum::SOFTWARE->getLabel())->toBe(__('Software Partner'))
        ->and(NetworkCategoryEnum::INFRASTRUCTURE->getLabel())->toBe(__('Infrastructure Partner'))
        ->and(NetworkCategoryEnum::SPONSORING->getLabel())->toBe(__('Sponsoring & Community'))
        ->and(NetworkCategoryEnum::CERTIFICATION->getLabel())->toBe(__('Certifications & Memberships'));
})->group('enums', 'network-category-enum');

it('returns correct cases array', function () {

    $expectedCases = [
        NetworkCategoryEnum::COLLABORATION,
        NetworkCategoryEnum::SOFTWARE,
        NetworkCategoryEnum::INFRASTRUCTURE,
        NetworkCategoryEnum::SPONSORING,
        NetworkCategoryEnum::CERTIFICATION,
    ];

    expect(NetworkCategoryEnum::cases())->toBe($expectedCases);

})->group('enums', 'network-category-enum');
