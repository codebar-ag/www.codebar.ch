<?php

namespace App\Sitemap;

use App\DTO\PageDTO;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapBuilder
{
    private Sitemap $sitemap;

    private Carbon $lastModificationDate;

    public function __construct()
    {
        $this->lastModificationDate = now()->startOfMonth();

        $this->sitemap = Sitemap::create();
    }

    public function addItem(PageDTO $pageDTO): void
    {
        $url = Url::create($pageDTO->url());
        $url->setPriority(1.0);
        $url->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
        $url->setLastModificationDate($this->lastModificationDate);
        $url->addImage($pageDTO->image, $pageDTO->title);

        foreach ($pageDTO->referencePages as $referencePage) {
            $url->addAlternate($referencePage->url(), $referencePage->locale);
        }

        $this->sitemap->add($url);

    }

    public function toXml(): string
    {
        return $this->sitemap->render();
    }
}
