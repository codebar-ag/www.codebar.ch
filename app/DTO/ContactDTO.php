<?php

namespace App\DTO;

use App\Models\Contact;
use Illuminate\Support\Arr;

class ContactDTO
{
    public function __construct(
        public readonly string $locale,
        public readonly string $section,
        public readonly string $name,
        public readonly ?string $role,
        public readonly string $image,
        public readonly array $icons,
    ) {}

    public static function fromModel(Contact $contact, string $section, string $locale): self
    {
        $role = Arr::get($contact->sections, "$section.role.$locale");

        return new self(
            name: $contact->name,
            role: $role,
            locale: $locale,
            image: $contact->image,
            icons: $contact->icons ?? [],
            section: $section,
        );
    }
}
