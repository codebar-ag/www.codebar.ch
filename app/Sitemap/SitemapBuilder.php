<?php

namespace App\Sitemap;

use App\DTO\PageDTO;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapBuilder
{
    private Sitemap $sitemap;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();
    }

    public function addItem(PageDTO $page): void
    {
        $url = Url::create(url: $page->url());
        $url->setPriority(priority: 1.0);
        $url->setChangeFrequency(changeFrequency: Url::CHANGE_FREQUENCY_WEEKLY);
        $url->setLastModificationDate(lastModificationDate: $page->lastModificationDate);

        if (filled($page->image)) {
            $url->addImage(url: $page->image, caption: $page->title);
        }

        if ($page->referencePages && ($firstReference = $page->referencePages->first())) {
            $page->referencePages->each(function (PageDTO $page) use ($url): void {
                $url->addAlternate(url: $page->url(), locale: str_replace('_', '-', $page->locale));
            });

            $url->addAlternate(url: $firstReference->url(), locale: 'x-default');
        }

        $this->sitemap->add($url);
    }

    public function toXml(): string
    {
        $xml = $this->sitemap->render();

        $dom = new \DOMDocument(version: '1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML(source: $xml);

        $formatted = $dom->saveXML();

        if ($formatted === false) {
            throw new \RuntimeException('Unable to generate sitemap XML.');
        }

        return $formatted;
    }
}
