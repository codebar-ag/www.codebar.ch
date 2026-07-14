<?php

use App\DTO\PageDTO;
use App\Sitemap\SitemapBuilder;
use Illuminate\Support\Carbon;

it('adds a page and renders valid xml containing its url', function () {
    $page = new PageDTO(
        locale: 'de_CH',
        routeKey: 'start.index',
        routeName: 'de-ch.start.index',
        title: 'Start',
        lastModificationDate: Carbon::now(),
    );

    $builder = new SitemapBuilder;
    $builder->addItem($page);

    $xml = $builder->toXml();

    expect($xml)->toContain($page->url());
    expect(simplexml_load_string($xml))->not->toBeFalse();
})->group('unit', 'sitemap');

it('adds alternate locale links for reference pages', function () {
    $page = new PageDTO(
        locale: 'de_CH',
        routeKey: 'start.index',
        routeName: 'de-ch.start.index',
        title: 'Start',
        lastModificationDate: Carbon::now(),
    );

    $reference = new PageDTO(
        locale: 'en_CH',
        routeKey: 'start.index',
        routeName: 'en-ch.start.index',
        title: 'Start',
        lastModificationDate: Carbon::now(),
    );

    $page->referencePages = collect([$reference]);

    $builder = new SitemapBuilder;
    $builder->addItem($page);

    $xml = $builder->toXml();

    expect($xml)->toContain($reference->url());
})->group('unit', 'sitemap');
