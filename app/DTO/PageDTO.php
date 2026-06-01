<?php

namespace App\DTO;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PageDTO
{
    public function __construct(
        public readonly string $locale,
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?string $image = null,
        public readonly string $robots = 'index,follow',
        public readonly ?string $url = null,
        public readonly ?Carbon $lastModificationDate = null,
        public ?Collection $alternates = null,
    ) {}

    public function url(): ?string
    {
        return $this->url;
    }
}
