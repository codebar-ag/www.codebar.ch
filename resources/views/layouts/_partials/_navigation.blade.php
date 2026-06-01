@php
    $navItems = collect([
        ['label' => __('About'), 'href' => localized_route('about-us.index')],
        $configuration?->section_services ? ['label' => __('Services'), 'href' => localized_route('services.index')] : null,
        $configuration?->section_products ? ['label' => __('Products'), 'href' => localized_route('products.index')] : null,
        $configuration?->section_open_source ? ['label' => __('Open Source'), 'href' => localized_route('open-source.index')] : null,
        $configuration?->section_technologies ? ['label' => __('Technologies'), 'href' => localized_route('technologies.index')] : null,
        $configuration?->section_news ? ['label' => __('News'), 'href' => localized_route('news.index')] : null,
        ($configuration?->section_co_working ?? true) ? ['label' => __('Co-Working'), 'href' => localized_route('co-working.index')] : null,
        ['label' => __('Contact'), 'href' => localized_route('contact.index')],
    ])->filter()->values()->all();
@endphp

<header
    x-data="navigation"
    x-on:keydown.escape.window="closeAll()"
    class="sticky top-0 z-40 border-b border-zinc-200 bg-white/85 backdrop-blur-md"
>
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6 lg:px-8">
        <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}" class="group inline-flex items-center">
            @include('layouts._logos.codebar')
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="{{ __('Primary') }}">
            @foreach($navItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    class="rounded-md px-3 py-2 text-sm font-medium text-zinc-600 transition-colors hover:text-zinc-950"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            @if(!empty($locales) && count($locales) === 2)
                @php
                    $currentLocale = app()->getLocale();
                    $nextLocale = collect($locales)->first(fn ($l) => $l->value !== $currentLocale);
                    $isEnActive = $currentLocale === 'en_CH';
                    $switchLabel = __('Switch to :lang', ['lang' => $nextLocale->getLabel()]);
                @endphp
                <form method="POST" action="{{ route('language.update') }}" class="hidden lg:block">
                    @csrf
                    <input type="hidden" name="language" value="{{ $nextLocale->value }}">
                    <button
                        type="submit"
                        title="{{ $switchLabel }}"
                        aria-label="{{ $switchLabel }}"
                        data-lang-active="{{ $isEnActive ? 'en' : 'de' }}"
                        class="lang-toggle"
                    >
                        <span aria-hidden="true" class="lang-toggle-thumb"></span>
                        <span class="lang-toggle-label" data-lang="en">EN</span>
                        <span class="lang-toggle-label" data-lang="de">DE</span>
                    </button>
                </form>
            @endif

            <button
                type="button"
                x-on:click="toggleDrawer()"
                x-bind:aria-expanded="openDrawer.toString()"
                aria-controls="mobile-menu"
                class="inline-flex size-9 items-center justify-center rounded-md border border-zinc-200 text-zinc-700 hover:bg-zinc-50 lg:hidden"
                aria-label="{{ __('Menu') }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                    <path x-show="!openDrawer" fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/>
                    <path x-show="openDrawer" x-cloak fill-rule="evenodd" d="M4.28 3.22a.75.75 0 0 0-1.06 1.06L8.94 10l-5.72 5.72a.75.75 0 1 0 1.06 1.06L10 11.06l5.72 5.72a.75.75 0 1 0 1.06-1.06L11.06 10l5.72-5.72a.75.75 0 0 0-1.06-1.06L10 8.94 4.28 3.22Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    <div
        id="mobile-menu"
        x-show="openDrawer"
        x-cloak
        x-transition.opacity
        class="fixed inset-x-0 top-16 z-30 max-h-[calc(100vh-4rem)] overflow-y-auto border-b border-zinc-200 bg-white lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('Main menu') }}"
    >
        <div class="space-y-1 px-6 py-8">
            <a
                href="{{ localized_route('start.index') }}"
                x-on:click="closeDrawer()"
                class="block py-2 text-base font-medium text-zinc-950 hover:text-brand"
            >
                {{ __('Home') }}
            </a>
            @foreach($navItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    x-on:click="closeDrawer()"
                    class="block py-2 text-base font-medium text-zinc-700 hover:text-zinc-950"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            @if(!empty($locales) && count($locales) === 2)
                @php
                    $currentLocale = app()->getLocale();
                    $nextLocale = collect($locales)->first(fn ($l) => $l->value !== $currentLocale);
                    $isEnActive = $currentLocale === 'en_CH';
                    $switchLabel = __('Switch to :lang', ['lang' => $nextLocale->getLabel()]);
                @endphp
                <div class="mt-4 flex border-t border-zinc-200 pt-6">
                    <form method="POST" action="{{ route('language.update') }}">
                        @csrf
                        <input type="hidden" name="language" value="{{ $nextLocale->value }}">
                        <button
                            type="submit"
                            title="{{ $switchLabel }}"
                            aria-label="{{ $switchLabel }}"
                            data-lang-active="{{ $isEnActive ? 'en' : 'de' }}"
                            class="lang-toggle lang-toggle--lg"
                        >
                            <span aria-hidden="true" class="lang-toggle-thumb"></span>
                            <span class="lang-toggle-label" data-lang="en">EN</span>
                            <span class="lang-toggle-label" data-lang="de">DE</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</header>
