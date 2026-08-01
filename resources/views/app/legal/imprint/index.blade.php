<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Imprint')" :page="$page"/>

    <x-layout.section>
        <x-h2 :title="__('Company')"/>
        <x-card.address-card
                :lines="[config('company.legal_name'), __('Legal form AG'), config('company.uid')]"
                :link-href="config('company.zefix_url')"
                link-label="{{ __('Zefix') }}"/>
    </x-layout.section>

    <x-layout.section class="flex flex-col gap-6 sm:flex-row sm:flex-wrap sm:gap-12">
        <div>
            <x-h2 :title="__('Email')"/>
            <x-ui.link :href="'mailto:' . config('company.email')" :label="config('company.email')"
                       :title="__('Contact email address')" class="block"/>
        </div>
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-ui.link :href="'tel:' . config('company.phone.e164')" :label="config('company.phone.display')"
                       :title="__('Contact Phone number')" class="block"/>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Authorized representatives')"/>
        <x-ui.prose variant="legal">
            <ul>
                <li>Sebastian Bürgin-Fix</li>
                <li>Melanie Sabrina Bürgin-Fix</li>
            </ul>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Disclaimer')"/>
        <p class="text-gray-800">{{ __('Imprint disclaimer') }}</p>
    </x-layout.section>
</x-app-layout>
