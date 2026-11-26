<?php

declare(strict_types=1);

use App\Enums\InfrastructureFlowEnum;

it('lists the flows from busiest to rarest', function () {
    expect(array_column(InfrastructureFlowEnum::cases(), 'value'))->toBe(['constant', 'frequent', 'occasional', 'scheduled']);
})->group('enums', 'infrastructure');
