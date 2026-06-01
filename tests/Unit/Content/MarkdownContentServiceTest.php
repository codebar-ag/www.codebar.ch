<?php

use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->base = sys_get_temp_dir().'/content-test-'.bin2hex(random_bytes(4));
    @mkdir($this->base.'/news/en_CH', 0777, true);
    @mkdir($this->base.'/news/de_CH', 0777, true);

    file_put_contents($this->base.'/news/en_CH/hello.md', <<<'MD'
---
slug: hello
title: Hello
teaser: a teaser
order: 1
publishedAt: 2026-01-01
---

The body.
MD);

    file_put_contents($this->base.'/news/en_CH/draft.md', <<<'MD'
---
slug: draft
title: Draft
published: false
---

Drafty.
MD);

    Cache::flush();
    $this->service = new MarkdownContentService($this->base, 0);
});

afterEach(function () {
    @unlink($this->base.'/news/en_CH/hello.md');
    @unlink($this->base.'/news/en_CH/draft.md');
    @rmdir($this->base.'/news/en_CH');
    @rmdir($this->base.'/news/de_CH');
    @rmdir($this->base.'/news');
    @rmdir($this->base);
});

it('lists published items only', function () {
    $items = $this->service->all('news', LocaleEnum::EN);

    expect($items)->toHaveCount(1);
    expect($items->first()->slug)->toBe('hello');
});

it('finds an item by slug', function () {
    $item = $this->service->find('news', LocaleEnum::EN, 'hello');

    expect($item)->not->toBeNull();
    expect($item->title)->toBe('Hello');
    expect($item->teaser)->toBe('a teaser');
    expect($item->body)->toContain('The body.');
});

it('returns null for missing slug', function () {
    expect($this->service->find('news', LocaleEnum::EN, 'nope'))->toBeNull();
});

it('isolates locales', function () {
    expect($this->service->all('news', LocaleEnum::DE))->toHaveCount(0);
});
