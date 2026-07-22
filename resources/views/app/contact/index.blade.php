<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Contact')" :intro="__('components.contact.header')"/>

    <x-layout.section class="flex flex-wrap gap-12">
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-ui.link href="tel:0041615156090" label="{{ __('+41 61 515 60 90') }}" class="block"/>
        </div>
        <div>
            <x-h2 :title="__('Email')"/>
            <x-ui.link href="mailto:info@codebar.ch?subject=Hello%20World!" label="{{ __('info(at)codebar.ch') }}"
                       class="block"/>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Locations')"/>
        <x-layout.grid :cols="2" gap="gap-6 sm:gap-4">
            <x-card.address-card
                    :title="__('Zunzgen')"
                    :label="__('Headquarter')"
                    :lines="['codebar Solutions AG', 'Hauptstrasse 91', 'CH-4455 Zunzgen']"
                    link-href="https://maps.app.goo.gl/d9iK5vCrHHAHUcvx6"
                    link-label="{{ __('Google Maps') }} — {{ __('Zunzgen') }}"/>
            <x-card.address-card
                    :title="__('Oberwil')"
                    :label="__('Office')"
                    :lines="['codebar Solutions AG', 'Langegasse 39', 'CH-4104 Oberwil']"
                    link-href="https://maps.app.goo.gl/1ndrUgUvw2pxxekUA"
                    link-label="{{ __('Google Maps') }} — {{ __('Oberwil') }}"/>
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-opening-hours :hours="$openingHours"/>
    </x-layout.section>
</x-app-layout>
