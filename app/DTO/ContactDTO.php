<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\ContactSectionEnum;
use App\Models\Contact;
use Illuminate\Support\Arr;

class ContactDTO
{
    /**
     * @param  array<array-key, mixed>  $icons
     */
    public function __construct(
        public readonly string $locale,
        public readonly ContactSectionEnum $section,
        public readonly string $key,
        public readonly string $name,
        public readonly ?string $role,
        public readonly string $image,
        public readonly array $icons,
    ) {}

    public static function fromModel(Contact $contact, ContactSectionEnum $section, string $locale): self
    {
        $role = Arr::get($contact->sections ?? [], "{$section->value}.role.{$locale}");

        return new self(
            key: $contact->key,
            name: $contact->name,
            role: is_string($role) ? $role : null,
            locale: $locale,
            image: $contact->image,
            icons: $contact->icons ?? [],
            section: $section,
        );
    }
}
