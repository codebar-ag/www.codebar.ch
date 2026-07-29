@props(['locales' => [], 'separator' => true])

{{-- Real links, not a form: crawlers cannot submit forms, so a form would leave the
     two language versions connected only by the hreflang tags in <head>.
     SetLanguage reads the locale from the URL and persists it, so no POST is needed. --}}
@if(! empty($locales))
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
        @foreach($locales as $language)
            <a href="{{ locale_switch_url($language->value) }}"
               hreflang="{{ str_replace('_', '-', $language->value) }}"
               @if($language->value === app()->getLocale()) aria-current="true" @endif
               title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}"
               @class([
                   'grid min-h-control min-w-control place-items-center rounded-pill px-2 text-base transition focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand sm:min-h-0 sm:min-w-0',
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
