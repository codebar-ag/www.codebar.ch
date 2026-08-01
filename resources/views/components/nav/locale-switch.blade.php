@props(['locales' => [], 'separator' => true])

@if(! empty($locales))
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
        @foreach($locales as $language)
            <a href="{{ locale_switch_url($language->value) }}"
               hreflang="{{ str_replace('_', '-', $language->value) }}"
               @if($language->value === app()->getLocale()) aria-current="true" @endif
               title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}"
               @class([
                   'grid tap-target min-w-control place-items-center rounded-pill px-2 text-base transition focus-ring sm:min-w-0',
                   'font-semibold text-brand' => $language->value === app()->getLocale(),
                   'text-gray-800 hover:text-brand' => $language->value !== app()->getLocale(),
               ])>
                {{ $language->getLabel() }}
            </a>
            @if($separator && ! $loop->last)
                <span class="font-light text-gray-300" aria-hidden="true">|</span>
            @endif
        @endforeach
    </div>
@endif
