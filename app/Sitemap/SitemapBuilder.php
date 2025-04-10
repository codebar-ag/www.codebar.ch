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

    public function addItem(PageDTO $page): void
    {
        $url = Url::create($page->url());
        $url->setPriority(1.0);
        $url->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
        $url->setLastModificationDate($this->lastModificationDate);
        $url->addImage($page->image, $page->title);

        if (! empty($page->referencePages) && $page->referencePages->count()) {
            $page->referencePages->each(function ($page) use ($url) {
                $url->addAlternate($page->url(), $page->locale);
            });
        }

        $this->sitemap->add($url);

    }

    public function toXml(): string
    {
        $xml = $this->sitemap->render();

        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        return $dom->saveXML();
    }
}
