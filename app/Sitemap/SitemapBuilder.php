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
        $url = Url::create($page->url() ?? '/');
        $url->setPriority(1.0);
        $url->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
        $url->setLastModificationDate($page->lastModificationDate ?? now()->startOfMonth());

        if ($page->image !== null) {
            $url->addImage($page->image, $page->title);
        }

        if ($page->alternates !== null && $page->alternates->isNotEmpty()) {
            foreach ($page->alternates as $alternate) {
                $url->addAlternate($alternate['url'], $alternate['locale']);
            }
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
