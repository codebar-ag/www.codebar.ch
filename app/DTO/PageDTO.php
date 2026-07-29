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
        /**
         * Set only on editorial pages. Their presence is what switches the
         * Open Graph type from "website" to "article" — a shared article card
         * carries a byline and a date, a page card does not.
         */
        public ?Carbon $publishedAt = null,
        public ?string $authorName = null,
    ) {}

    public function isArticle(): bool
    {
        return $this->publishedAt instanceof Carbon;
    }

    public function url(): string
    {
        return route($this->routeName, $this->routeParameters, true);
    }
}
