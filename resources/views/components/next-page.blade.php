@php
    $routeName = \Illuminate\Support\Str::after(request()->route()?->getName() ?? '', '.');

    $next = \App\Support\PageNavigation::next($routeName);
@endphp

@if($next)
    <x-layout.section>
        <x-h2 :title="__('components.explore.title')"/>
        <x-layout.grid>
            <x-card.nav-card :url="localized_route($next['route'])" :label="$next['label']" :teaser="$next['teaser']"/>
        </x-layout.grid>
    </x-layout.section>
@endif
