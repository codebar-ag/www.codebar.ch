@php
    $cards = collect(\App\Support\PageNavigation::pages())
        ->reject(fn (array $page) => $page['route'] === 'start.index');
@endphp

<x-layout.section>
    <x-h2 :title="__('components.explore.title')"/>

    <x-layout.grid class="mt-4">
        @foreach ($cards as $card)
            <x-card.nav-card :url="localized_route($card['route'])" :label="$card['label']" :teaser="$card['teaser']"/>
        @endforeach
    </x-layout.grid>
</x-layout.section>
