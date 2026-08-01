<?php

declare(strict_types=1);

use App\DTO\ContactDTO;
use App\Enums\ContactSectionEnum;
use App\Models\Contact;

it('builds a ContactDTO from a Contact model', function () {
    $contact = Contact::factory()->create([
        'name' => 'Sebastian',
        'image' => 'https://example.com/image.jpg',
        'sections' => [
            'employees' => [
                'role' => [
                    'de_CH' => 'Software-Architekt',
                ],
            ],
        ],
        'icons' => ['email' => 'sebastian@example.com'],
    ]);

    $dto = ContactDTO::fromModel($contact, ContactSectionEnum::EMPLOYEES, 'de_CH');

    expect($dto->name)->toBe('Sebastian');
    expect($dto->role)->toBe('Software-Architekt');
    expect($dto->locale)->toBe('de_CH');
    expect($dto->image)->toBe('https://example.com/image.jpg');
    expect($dto->icons)->toBe(['email' => 'sebastian@example.com']);
})->group('unit', 'dto');

it('returns a null role when none is defined for the section and locale', function () {
    $contact = Contact::factory()->create([
        'sections' => [],
        'icons' => null,
    ]);

    $dto = ContactDTO::fromModel($contact, ContactSectionEnum::EMPLOYEES, 'de_CH');

    expect($dto->role)->toBeNull();
    expect($dto->icons)->toBe([]);
})->group('unit', 'dto');
