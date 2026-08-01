@use(App\Enums\CookieNameEnum;use App\Enums\LocaleEnum;use Illuminate\Support\Str)

@php
    $subtag = fn (string $locale) => Str::before($locale, '_');

    $current = app()->getLocale();
    $alternates = array_values(array_filter(
        LocaleEnum::cases(),
        fn (LocaleEnum $locale) => $locale->value !== $current,
    ));
@endphp

@if(! empty($alternates))
    {{-- Server-rendered hidden and revealed only by the Alpine component: the page is
         response-cached, so which visitor sees this cannot be decided here. --}}
    <div x-data="languageSuggestion"
         data-cookie="{{ CookieNameEnum::ENTRY_REDIRECT->value }}"
         data-language="{{ $subtag($current) }}"
         data-fallback="{{ $subtag(LocaleEnum::EN->value) }}"
         hidden>
        @foreach($alternates as $alternate)
            <div data-language="{{ $subtag($alternate->value) }}" hidden
                 lang="{{ str_replace('_', '-', $alternate->value) }}"
                 class="mb-8 flex flex-wrap items-center gap-x-4 gap-y-2 rounded-panel bg-gray-100 px-4 py-3 text-base ring-1 ring-border ring-inset">
                <p class="text-gray-800">
                    {{ __('components.language_suggestion.message', [], $alternate->value) }}
                </p>

                <x-ui.link :href="locale_switch_url($alternate->value)"
                           hreflang="{{ str_replace('_', '-', $alternate->value) }}"
                           class="font-medium text-brand">
                    {{ __('components.language_suggestion.action', [], $alternate->value) }}
                </x-ui.link>

                <button x-on:click="dismiss" type="button"
                        class="ml-auto grid tap-target min-w-control place-items-center rounded-pill text-gray-500 transition hover:text-gray-800 focus-ring">
                    <span class="sr-only">{{ __('components.language_suggestion.dismiss', [], $alternate->value) }}</span>
                    <x-icon.close class="size-4"/>
                </button>
            </div>
        @endforeach
    </div>
@endif
