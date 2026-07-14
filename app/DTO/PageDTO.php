<?php

namespace App\DTO;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PageDTO
{
    /**
     * @param  Collection<int, self>|null  $referencePages
     */
    public function __construct(
        public string $locale,
        public string $routeKey,
        public string $routeName,
        public string $title,
        public Carbon $lastModificationDate,
        public string $robots = 'index,follow',
        public mixed $routeParameters = [],
        public ?string $description = null,
        public ?string $image = null,
        public ?Collection $referencePages = null,
    ) {}

    public function url(): string
    {
        return route($this->routeName, $this->routeParameters, true);
    }
}
