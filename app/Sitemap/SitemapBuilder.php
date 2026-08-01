<?php

declare(strict_types=1);

namespace App\Sitemap;

use App\DTO\PageDTO;
use App\Support\NewsImage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapBuilder
{
    private Sitemap $sitemap;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();
    }

    /**
     * Relative importance per section. Priority is only a hint to crawlers, but
     * emitting 1.0 for every URL — homepage and a numbers table alike — tells
     * them nothing at all.
     */
    private const array PRIORITIES = [
        'start.index' => 1.0,
        'services.index' => 0.9,
        'contact.index' => 0.9,
        'about-us.index' => 0.8,
        'news.index' => 0.8,
        'news.show' => 0.7,
        'ai.index' => 0.7,
        'network.index' => 0.7,
        // 'open-source.index' => 0.6, — disabled controller, see OpenSourceIndexController.
        // 'open-source.show' => 0.5, — disabled controller, see OpenSourceShowController.
        'jobs.index' => 0.6,
        'ai.llm.index' => 0.6,
        // 'network.show' => 0.5, — disabled controller, see NetworkShowController.
        'media.index' => 0.4,
        'ai.llm.analytics.index' => 0.3,
        'legal.imprint.index' => 0.2,
        'legal.privacy.index' => 0.2,
        'legal.terms.index' => 0.2,
    ];

    /**
     * Legal texts and media assets change far less often than the news index.
     */
    private const array CHANGE_FREQUENCIES = [
        'news.index' => Url::CHANGE_FREQUENCY_WEEKLY,
        'ai.llm.analytics.index' => Url::CHANGE_FREQUENCY_DAILY,
        'legal.imprint.index' => Url::CHANGE_FREQUENCY_YEARLY,
        'legal.privacy.index' => Url::CHANGE_FREQUENCY_YEARLY,
        'legal.terms.index' => Url::CHANGE_FREQUENCY_YEARLY,
        'media.index' => Url::CHANGE_FREQUENCY_YEARLY,
    ];

    private const float DEFAULT_PRIORITY = 0.5;

    public function addItem(PageDTO $page): void
    {
        $url = Url::create(url: $page->url());
        $url->setPriority(priority: self::PRIORITIES[$page->routeKey] ?? self::DEFAULT_PRIORITY);
        $url->setChangeFrequency(
            changeFrequency: self::CHANGE_FREQUENCIES[$page->routeKey] ?? Url::CHANGE_FREQUENCY_MONTHLY
        );
        $url->setLastModificationDate(lastModificationDate: $page->lastModificationDate);

        $image = NewsImage::crawlable($page->image, config()->integer('seo.image_width'));

        if ($image !== null) {
            $url->addImage(url: $image, caption: $page->title);
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
