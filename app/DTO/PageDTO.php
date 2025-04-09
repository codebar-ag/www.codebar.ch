<?php

namespace App\DTO;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PageDTO
{
    public function __construct(
        public string $locale,
        public string $routeName,
        public string $title,
        public Carbon $lastModificationDate,
        public string $robots = 'noindex,nofollow',
        public mixed $routeParameters = [],
        public ?string $description = null,
        public ?string $image = null,
        public Collection $referencePages = new Collection
    ) {}

    public function url(): string
    {
        return localized_route($this->routeName, $this->routeParameters, true, $this->locale);
    }
}
