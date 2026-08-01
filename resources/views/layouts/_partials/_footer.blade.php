@php
    $columns = [
        __('Legal') => [
            ['route' => 'legal.privacy.index', 'label' => __('Privacy')],
            ['route' => 'legal.imprint.index', 'label' => __('Imprint')],
            ['route' => 'legal.terms.index', 'label' => __('Terms')],
        ],
        __('Company') => [
            ['route' => 'jobs.index', 'label' => __('Jobs')],
        ],
        __('Resources') => [
            ['route' => 'media.index', 'label' => __('Media')],
        ],
        __('Network') => [
            ['route' => 'network.request.index', 'label' => __('Profile'), 'rel' => 'nofollow'],
        ],
    ];
@endphp

<footer class="relative left-1/2 mt-12 w-screen -translate-x-1/2 border-t border-border bg-surface text-lg md:mt-20">
    <div class="mx-auto flex w-full max-w-frame flex-col gap-8 px-4 py-12 sm:px-6 md:py-16 lg:px-8">

        <nav aria-label="{{ __('Footer') }}" class="flex flex-wrap items-start gap-x-18 gap-y-4">
            @foreach($columns as $heading => $links)
                <div>
                    <h2 class="text-subheading font-semibold text-gray-900">{{ $heading }}</h2>
                    <ul class="mt-1 list-none">
                        @foreach($links as $link)
                            <li>
                                <x-ui.link :href="localized_route($link['route'])" :label="$link['label']"
                                           :rel="$link['rel'] ?? null"
                                           class="inline-flex tap-target items-center"/>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        <div class="text-center text-base text-muted md:text-left">
            <span title="{{ app()->getLocale() }}">© {{ date('Y') }} codebar Solutions AG</span>
        </div>
    </div>
</footer>
