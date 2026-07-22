@use(App\Enums\LocaleEnum;use Illuminate\Support\Facades\Config)

@php
    $display = Config::get('seeder.seeder.paperflakes');

    $showme_url = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://showme.docuware.com/en-gb/interactive-tours',
        default => 'https://showme.docuware.com/de/interactive-tours',
    };

    $product_url = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://www.paperflakes.ch/services/en_CH/dms-ecm-docuware',
        default => 'https://www.paperflakes.ch/dienstleistungen/de_CH/dms-ecm-docuware',
    };
@endphp

@if ($display)
    <x-band.cta-band
            :title="__('components.docuware.showme.title')"
            :body="__('components.docuware.showme.teaser')"
            :brand-color="$configuration?->company_primary_color">
        <x-ui.button variant="primary" :href="$showme_url" target="_blank">
            {{ __('components.docuware.showme.buttons.discover_now') }}
            <x-icon.external-link class="ml-1 size-4"/>
        </x-ui.button>

        <x-ui.button variant="outline" :href="$product_url"
                     :label="__('components.docuware.showme.buttons.more')"/>
    </x-band.cta-band>
@endif
