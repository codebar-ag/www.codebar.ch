<?php

namespace App\Actions;

use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use Illuminate\Support\Collection;

class ViewDataAction
{
    public function __construct(private readonly MarkdownContentService $content) {}

    public function configuration(string $locale): object
    {
        return site_configuration();
    }

    public function products(string $locale): Collection
    {
        return $this->content->all('products', LocaleEnum::from($locale));
    }

    public function services(string $locale): Collection
    {
        return $this->content->all('services', LocaleEnum::from($locale));
    }

    public function news(string $locale): Collection
    {
        return $this->content->all('news', LocaleEnum::from($locale));
    }

    public function technologies(string $locale): Collection
    {
        return $this->content->all('technologies', LocaleEnum::from($locale));
    }

    public function openSource(string $locale): Collection
    {
        return $this->content->all('open-source', LocaleEnum::from($locale));
    }

    public function contacts(string $locale): object
    {
        $build = fn (array $list) => collect($list)->map(fn (array $member) => (object) [
            'name' => $member['name'],
            'role' => is_array($member['role'] ?? null) ? ($member['role'][$locale] ?? null) : ($member['role'] ?? null),
            'image' => $member['image'] ?? null,
            'icons' => $member['icons'] ?? [],
        ]);

        return (object) [
            'employees' => $build(config('team.employees', [])),
            'collaborations' => $build(config('team.collaborations', [])),
            'board_members' => $build(config('team.board_members', [])),
        ];
    }

    public function milestones(string $locale): Collection
    {
        return collect(config('history.milestones', []))->map(fn (array $milestone) => (object) [
            'year' => (int) $milestone['year'],
            'title' => $milestone['title'] ?? '',
            'body' => $milestone['body'] ?? '',
        ]);
    }

    public function pillars(string $locale): Collection
    {
        return collect(config('pillars.items', []))->map(fn (array $pillar) => (object) [
            'eyebrow' => $pillar['eyebrow'] ?? null,
            'title' => $pillar['title'] ?? '',
            'teaser' => $pillar['teaser'] ?? null,
        ]);
    }
}
