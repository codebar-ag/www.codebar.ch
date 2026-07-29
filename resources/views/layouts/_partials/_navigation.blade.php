<nav class="mt-12 px-4 text-xl md:px-0 md:text-2xl" aria-label="{{ __('Menu') }}" x-data="navigation" @keydown.escape.window="close">
    <div class="flex items-center justify-between">

        <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}"
           class="group inline-block max-w-1/2 rounded-pill focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
            @include('layouts._logos._codebar')
        </a>

        <button @click="toggle" type="button"
                aria-controls="mobile-navigation" x-bind:aria-expanded="aria_expanded"
                class="-mr-2 grid size-control place-items-center rounded-pill transition hover:text-brand focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand md:hidden">
            <span class="sr-only">{{ __('Open menu') }}</span>
            <x-icon.menu class="size-7"/>
        </button>

        <x-nav.locale-switch :locales="$locales" class="hidden text-lg md:flex"/>
    </div>

    <div class="mt-2 hidden items-center justify-between md:flex">
        @include('layouts._partials._navigation_desktop')
    </div>

    <div class="md:hidden">
        @include('layouts._partials._navigation_mobile')
    </div>
</nav>
