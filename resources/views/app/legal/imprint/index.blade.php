<x-app-layout :page="$page">
    <x-h1 :title="__('Imprint')"/>

    <x-layout.section>
        <x-h2 :title="__('Company')"/>
        <x-card.address-card
                :lines="['codebar Solutions AG', __('Legal form AG'), 'CHE-257.955.682']"
                link-href="https://zefix.ch/de/search/entity/list/firm/1466584"
                link-label="{{ __('Zefix') }}"/>
    </x-layout.section>

    <x-layout.section class="flex flex-wrap gap-12">
        <div>
            <x-h2 :title="__('Email')"/>
            <x-ui.link href="mailto:info@codebar.ch" label="{{ __('info(at)codebar.ch') }}" class="block"/>
        </div>
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-ui.link href="tel:0041615156090" label="{{ __('+41 61 515 60 90') }}" class="block"/>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Authorized representatives')"/>
        <x-ui.prose variant="legal">
            <ul>
                <li>Sebastian Bürgin</li>
                <li>Melanie Sabrina Bürgin</li>
            </ul>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Disclaimer')"/>
        <p class="text-gray-800">{{ __('Imprint disclaimer') }}</p>
    </x-layout.section>
</x-app-layout>
