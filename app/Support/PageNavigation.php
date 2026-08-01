<?php

declare(strict_types=1);

namespace App\Support;

class PageNavigation
{
    /**
     * The main pages in visitor reading order.
     *
     * @return array<int, array{route: string, label: string, teaser: string}>
     */
    public static function pages(): array
    {
        return [
            ['route' => 'start.index', 'label' => __('Home'), 'teaser' => __('components.explore.home')],
            ['route' => 'services.index', 'label' => __('Services'), 'teaser' => __('components.explore.services')],
            ['route' => 'about-us.index', 'label' => __('Team'), 'teaser' => __('components.explore.team')],
            ['route' => 'news.index', 'label' => __('News'), 'teaser' => __('components.explore.news')],
            ['route' => 'ai.index', 'label' => __('AI'), 'teaser' => __('components.explore.ai')],
            ['route' => 'network.index', 'label' => __('Network'), 'teaser' => __('components.explore.network')],
            ['route' => 'contact.index', 'label' => __('Contact'), 'teaser' => __('components.explore.contact')],
        ];
    }

    /**
     * Where a page sends its reader next, curated per page rather than derived from the
     * menu order. Sources may sit outside pages(); every target must be one of them.
     *
     * @return array<string, string>
     */
    public static function chain(): array
    {
        return [
            'services.index' => 'about-us.index',
            'about-us.index' => 'contact.index',
            'news.index' => 'ai.index',
            'news.show' => 'services.index',
            'ai.index' => 'services.index',
            'ai.llm.index' => 'ai.index',
            'ai.llm.analytics.index' => 'services.index',
            'network.index' => 'contact.index',
            'jobs.index' => 'about-us.index',
            'media.index' => 'network.index',
        ];
    }

    /**
     * @return array{route: string, label: string, teaser: string}|null
     */
    public static function next(string $routeName): ?array
    {
        $target = self::chain()[$routeName] ?? null;

        if ($target === null) {
            return null;
        }

        foreach (self::pages() as $page) {
            if ($page['route'] === $target) {
                return $page;
            }
        }

        return null;
    }
}
