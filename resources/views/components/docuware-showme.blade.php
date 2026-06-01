@use(App\Enums\LocaleEnum)

@php
    $showmeUrl = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://showme.docuware.com/en-gb/interactive-tours',
        default => 'https://showme.docuware.com/de/interactive-tours',
    };

    $productUrl = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://www.codebar.ch/en-ch/services',
        default => 'https://www.codebar.ch/de-ch/dienstleistungen',
    };
@endphp

<x-ui.section>
    <x-ui.cta
        :title="__('components.docuware.showme.title')"
        :teaser="__('components.docuware.showme.teaser')"
    >
        <x-ui.button
            :href="$showmeUrl"
            :label="__('components.docuware.showme.buttons.discover_now')"
            target="_blank"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
        </x-ui.button>

        <x-ui.button
            :href="$productUrl"
            :label="__('components.docuware.showme.buttons.more')"
            variant="secondary"
        />
    </x-ui.cta>
</x-ui.section>
