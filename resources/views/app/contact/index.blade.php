<x-app-layout :page="$page" :schema="$schema" :preconnect-cloudinary="(bool) $contactPerson">
    <x-layout.page-header :title="__('Contact')" :intro="__('components.contact.header')"/>

    <x-layout.section>
        <x-h2 :title="__('How to reach us')"/>

        <div class="flex flex-col gap-3 sm:flex-row">
            <x-ui.button :href="'tel:' . config('company.phone.e164')" size="lg" :title="__('Contact Phone number')">
                <x-icon.phone/>
                {{ config('company.phone.display') }}
            </x-ui.button>

            <x-ui.button :href="'mailto:' . config('company.email') . '?subject=Hello%20World!'" variant="outline"
                         size="lg" :title="__('Contact email address')">
                <x-icon.email/>
                {{ config('company.email') }}
            </x-ui.button>
        </div>

        @if($contactPerson)
            <div class="mt-8 max-w-md">
                <x-h2 :title="__('Your contact person')"/>
                <x-card.person-card
                        :name="$contactPerson->name"
                        :role="$contactPerson->role"
                        :icons="$contactPerson->icons"
                        :image="$contactPerson->image"/>
            </div>
        @endif
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
