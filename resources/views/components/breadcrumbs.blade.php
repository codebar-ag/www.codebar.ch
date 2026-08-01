@props(['items' => []])

@php
    // Home is implicit — callers pass only the trail below it. A crumb without a URL is
    // the page you are on: never a link, and marked aria-current="page". A page whose
    // own title is too long for a trail (an article headline) simply leaves itself out
    // and ends on its parent, which stays a link.
    $crumbs = collect($items)->filter(fn ($item) => filled($item['label'] ?? null))->values();
@endphp

@if($crumbs->isNotEmpty())
    <nav aria-label="{{ __('Breadcrumb') }}" {{ $attributes->merge(['class' => 'mb-3']) }}>
        <ol class="flex flex-wrap items-center gap-x-2 text-sm text-muted">
            <li class="flex items-center gap-2">
                <x-ui.link :href="localized_route('start.index')" :label="__('Home')"
                           class="inline-flex tap-target items-center"/>
                <span class="text-gray-300" aria-hidden="true">/</span>
            </li>

            @foreach($crumbs as $crumb)
                <li class="flex items-center gap-2">
                    @if(blank($crumb['url'] ?? null))
                        <span class="inline-flex tap-target items-center font-medium text-gray-800"
                              @if($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                    @else
                        <x-ui.link :href="$crumb['url']" :label="$crumb['label']"
                                   class="inline-flex tap-target items-center"/>
                    @endif

                    @unless($loop->last)
                        <span class="text-gray-300" aria-hidden="true">/</span>
                    @endunless
                </li>
            @endforeach
        </ol>
    </nav>
@endif
