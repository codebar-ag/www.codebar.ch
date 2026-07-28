<nav class="mt-12 text-xl md:text-2xl px-4 md:px-0" aria-label="{{ __('Menu') }}" x-data="navigation" @keydown.escape.window="close">
    <div class="flex justify-between items-center">

        <a href="{{ localized_route('start.index') }}" title="Start page" class="group inline-block max-w-1/2">
            @include('layouts._logos._codebar')
        </a>

        <button @click="toggle" type="button"
                aria-controls="mobile-navigation" x-bind:aria-expanded="aria_expanded"
                class="md:hidden p-2 -mr-2 hover:text-brand transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand rounded">
            <span class="sr-only">{{ __('Open menu') }}</span>
            <x-icon.menu class="size-7"/>
        </button>

        @if(!empty($locales))
            <div class="hidden md:flex gap-2 text-lg items-center">
                {{-- Real links, not a form: crawlers cannot submit forms, so a
                     form would leave the two language versions connected only by
                     the hreflang tags in <head>. SetLanguage reads the locale
                     from the URL and persists it, so no POST is needed. --}}
                @foreach($locales as $language)
                    <a href="{{ locale_switch_url($language->value) }}"
                       hreflang="{{ str_replace('_', '-', $language->value) }}"
                       @if($language->value === app()->getLocale()) aria-current="true" @endif
                       class="hover:text-brand hover:font-semibold transition"
                       title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                        {{ $language->getLabel() }}
                    </a>
                    @if (!$loop->last)
                        <span class="text-gray-300 font-light" aria-hidden="true">|</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-2 hidden md:flex items-center justify-between">
        @include('layouts._partials._navigation_desktop')
    </div>

    <div class="md:hidden">
        @include('layouts._partials._navigation_mobile')
    </div>
</nav>
