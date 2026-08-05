<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-white p-1 shadow-sm ring-1 ring-zunscan-dark-gray/10']) }}>
    @foreach(\App\Enums\LocaleEnum::cases() as $locale)
        <a href="{{ zunscan_locale_switch_url($locale->value) }}"
           hreflang="{{ str_replace('_', '-', $locale->value) }}"
           @if($locale->value === app()->getLocale()) aria-current="true" @endif
           title="{{ $locale->getLabel() }}"
           @class([
               'grid min-h-control min-w-control place-items-center rounded-full px-3 text-sm font-bold uppercase transition',
               'bg-zunscan-light-blue text-white' => $locale->value === app()->getLocale(),
               'text-zunscan-dark-gray hover:bg-white/60' => $locale->value !== app()->getLocale(),
           ])>
            {{ $locale->getLabel() }}
        </a>
    @endforeach
</div>
