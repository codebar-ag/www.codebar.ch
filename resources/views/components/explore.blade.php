@php
    // News is part of the reading chain but not of this grid: the latest-articles block
    // sits directly above it on the start page, and start.index is the page we are on.
    $cards = collect(\App\Support\PageNavigation::pages())
        ->reject(fn (array $page) => in_array($page['route'], ['start.index', 'news.index'], true));
@endphp

<x-layout.section>
    <x-h2 :title="__('components.explore.title')"/>

    <x-layout.grid class="mt-4">
        @foreach ($cards as $card)
            <x-card.nav-card :url="localized_route($card['route'])" :label="$card['label']" :teaser="$card['teaser']"/>
        @endforeach
    </x-layout.grid>
</x-layout.section>
