@props(['items' => []])

@php
    // Home is implicit — callers pass only the trail below it. The last entry is the
    // page you are on: never a link, de-emphasised, and marked aria-current="page".
    $crumbs = collect($items)->filter(fn ($item) => filled($item['label'] ?? null))->values();
@endphp

@if($crumbs->isNotEmpty())
    <nav aria-label="{{ __('Breadcrumb') }}" {{ $attributes->merge(['class' => 'mb-3']) }}>
        <ol class="flex flex-wrap items-center gap-x-2 text-sm text-muted">
            <li class="flex items-center gap-2">
                <x-ui.link :href="localized_route('start.index')" :label="__('Home')"
                           class="inline-flex min-h-control items-center sm:min-h-0"/>
                <span class="text-gray-300" aria-hidden="true">/</span>
            </li>

            @foreach($crumbs as $crumb)
                <li class="flex items-center gap-2">
                    @if($loop->last || blank($crumb['url'] ?? null))
                        <span class="inline-flex min-h-control items-center font-medium text-gray-800 sm:min-h-0"
                              @if($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                    @else
                        <x-ui.link :href="$crumb['url']" :label="$crumb['label']"
                                   class="inline-flex min-h-control items-center sm:min-h-0"/>
                    @endif

                    @unless($loop->last)
                        <span class="text-gray-300" aria-hidden="true">/</span>
                    @endunless
                </li>
            @endforeach
        </ol>
    </nav>
@endif
