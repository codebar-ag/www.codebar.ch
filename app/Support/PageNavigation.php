<?php

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
            ['route' => 'ai.index', 'label' => __('AI'), 'teaser' => __('components.explore.ai')],
            ['route' => 'network.index', 'label' => __('Network'), 'teaser' => __('components.explore.network')],
            ['route' => 'contact.index', 'label' => __('Contact'), 'teaser' => __('components.explore.contact')],
        ];
    }

    /**
     * @return array{route: string, label: string, teaser: string}|null
     */
    public static function next(string $routeName): ?array
    {
        $pages = self::pages();

        foreach ($pages as $index => $page) {
            if ($page['route'] === $routeName) {
                // No wrap-around: the last page (contact) shows no next card.
                return $pages[$index + 1] ?? null;
            }
        }

        return null;
    }
}
