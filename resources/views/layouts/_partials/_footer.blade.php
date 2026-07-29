@php
    // The footer menu as data, so a new entry is one line rather than a new block
    // of markup. Labels match the page titles they lead to.
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

{{-- min-h-[200px] reserved space against layout shift while the labels row loads;
     the row is inline SVG and paints with the document, so the reservation was
     holding open empty space for nothing. --}}
<footer class="my-12 bg-white text-lg md:my-20">
    <div class="flex flex-col gap-8">

        <nav aria-label="{{ __('Footer') }}" class="flex flex-wrap items-start gap-x-18 gap-y-4">
            @foreach($columns as $heading => $links)
                <div>
                    <h2 class="text-base font-semibold text-gray-800">{{ $heading }}</h2>
                    <ul class="mt-1 list-none">
                        @foreach($links as $link)
                            <li>
                                {{-- A footer link is as reachable with a thumb as any other. --}}
                                <x-ui.link :href="localized_route($link['route'])" :label="$link['label']"
                                           :rel="$link['rel'] ?? null"
                                           class="inline-flex min-h-control items-center sm:min-h-0"/>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        <div class="text-center text-base text-gray-500 md:text-left">
            <span title="{{ app()->getLocale() }}">© {{ date('Y') }} codebar Solutions AG</span>
        </div>
    </div>
</footer>
