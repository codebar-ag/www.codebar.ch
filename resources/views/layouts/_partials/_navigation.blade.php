@php
    $workItems = collect([
        $configuration?->section_services ? ['label' => __('Services'), 'href' => localized_route('services.index')] : null,
        $configuration?->section_products ? ['label' => __('Products'), 'href' => localized_route('products.index')] : null,
        $configuration?->section_technologies ? ['label' => __('Technologies'), 'href' => localized_route('technologies.index')] : null,
        $configuration?->section_open_source ? ['label' => __('Open Source'), 'href' => localized_route('open-source.index')] : null,
    ])->filter()->values()->all();

    $companyItems = collect([
        ['label' => __('About us'), 'href' => localized_route('about-us.index')],
        ['label' => __('Media'), 'href' => localized_route('media.index')],
        ['label' => __('Jobs'), 'href' => localized_route('jobs.index')],
        ['label' => __('Contact'), 'href' => localized_route('contact.index')],
    ])->values()->all();

    $resourceItems = collect([
        $configuration?->section_news ? ['label' => __('News'), 'href' => localized_route('news.index')] : null,
        ['label' => __('Styleguide'), 'href' => route('styleguide.index')],
        ['label' => __('Terms'), 'href' => localized_route('legal.terms.index')],
        ['label' => __('Privacy'), 'href' => localized_route('legal.privacy.index')],
        ['label' => __('Imprint'), 'href' => localized_route('legal.imprint.index')],
    ])->filter()->values()->all();

    $navBuckets = [
        [
            'key' => 'home',
            'label' => __('Home'),
            'type' => 'link',
            'href' => localized_route('start.index'),
        ],
        [
            'key' => 'work',
            'label' => __('Work'),
            'type' => 'panel',
            'description' => __('What we build and maintain.'),
            'items' => $workItems,
        ],
        [
            'key' => 'company',
            'label' => __('Company'),
            'type' => 'panel',
            'description' => __('Who we are and where to reach us.'),
            'items' => $companyItems,
        ],
        [
            'key' => 'resources',
            'label' => __('Resources'),
            'type' => 'panel',
            'description' => __('Knowledge, docs, and legal resources.'),
            'items' => $resourceItems,
        ],
    ];
@endphp

<nav class="pt-8 md:pt-10" x-data="navigation" @keydown.escape.window="closeAll()">
    <div class="nav-shell menu-shell surface-card ui-region px-5 py-4 md:px-7">
        <div class="flex items-center justify-between gap-4">
            @if(filled($configuration?->key))
                <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}" class="nav-brand group inline-flex">
                    @include("layouts._logos.{$configuration->key}")
                </a>
            @endif

            <div class="flex items-center gap-3">
                @if(!empty($locales))
                    <div class="nav-locales hidden items-center gap-1.5 text-xs lg:flex lg:text-sm">
                        @foreach($locales as $language)
                            <form method="POST" action="{{ route('language.update') }}">
                                @csrf
                                <input type="hidden" name="language" value="{{ $language->value }}">
                                <button type="submit"
                                        class="brand-link cursor-pointer rounded px-2 py-1 transition"
                                        title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                                    {{ $language->getLabel() }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <button
                    type="button"
                    @click="toggleDrawer"
                    x-bind:aria-expanded="openDrawer.toString()"
                    aria-controls="mobile-main-nav"
                    class="nav-menu-toggle menu-shell surface-card inline-flex items-center gap-2 px-3 py-2 text-sm lg:hidden"
                >
                    <span>{{ __('Menu') }}</span>
                    <span class="transition-transform duration-300 ease-in-out" x-bind:class="menuIconRotation">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                  d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        @include('layouts._partials._navigation_desktop', ['navBuckets' => $navBuckets])
    </div>

    @include('layouts._partials._navigation_mobile', ['navBuckets' => $navBuckets])
</nav>
