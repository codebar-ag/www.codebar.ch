<x-app-layout :page="$page" :schema="$schema">
    <x-layout.page-header :title="__('Contact')" :intro="__('components.contact.header')"/>

    <x-layout.section class="flex flex-col gap-6 sm:flex-row sm:flex-wrap sm:gap-12">
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-ui.link href="tel:{{ config('company.phone.e164') }}" label="{{ __('+41 61 515 60 90') }}" class="block"/>
        </div>
        <div>
            <x-h2 :title="__('Email')"/>
            <x-ui.link href="mailto:{{ config('company.email') }}?subject=Hello%20World!"
                       label="{{ __('info(at)codebar.ch') }}"
                       class="block"/>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Locations')"/>
        <x-layout.grid :cols="2" gap="gap-6 sm:gap-4">
            @foreach($locations as $location)
                <x-card.address-card
                        :title="__($location['city'])"
                        :label="__($location['label'])"
                        :lines="[
                            config('company.legal_name'),
                            $location['street'],
                            $location['country'] . '-' . $location['postal_code'] . ' ' . $location['city'],
                        ]"
                        :link-href="$location['map_url']"
                        link-label="{{ __('Google Maps') }} — {{ __($location['city']) }}"/>
            @endforeach
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-opening-hours :hours="$openingHours"/>
    </x-layout.section>
</x-app-layout>
