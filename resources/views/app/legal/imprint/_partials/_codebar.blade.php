<x-h1 :title="__('Imprint')"/>

<x-section>
    <x-h2 :title="__('Company')"/>
    <address class="not-italic text-gray-800 text-lg leading-relaxed">
        <p class="font-semibold">codebar Solutions AG</p>
        <p class="font-light">{{ __('Legal form AG') }}</p>
        <p class="font-light">CHE-257.955.682</p>
    </address>

    <x-a-badge href="https://zefix.ch/de/search/entity/list/firm/1466584" label="{{ __('Zefix') }}" class-attributes="mt-1" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 size-3"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
        </svg>
    </x-a-badge>
</x-section>

<x-section-flex>
    <div>
        <x-h2 :title="__('Email')"/>
        <x-a href="mailto:info@codebar.ch" label="{{ __('info(at)codebar.ch') }}" classAttributes="block"/>
    </div>
    <div>
        <x-h2 :title="__('Phone')"/>
        <x-a href="tel:0041615156090" label="{{ __('+41 61 515 60 90') }}" classAttributes="block"/>
    </div>
</x-section-flex>

<x-section>
    <x-h2 :title="__('Authorized representatives')"/>
    <div class="space-y-6 text-gray-800">
        <div>
            <p class="font-semibold">Sebastian Bürgin</p>
            <p class="font-light">{{ __('Imprint Sebastian Burgin residence') }}</p>
            <p class="font-light">{{ __('Imprint Sebastian Burgin role') }}</p>
            <p class="font-light text-gray-600">{{ __('Imprint individual signature') }}</p>
        </div>
        <div>
            <p class="font-semibold">Melanie Sabrina Bürgin</p>
            <p class="font-light">{{ __('Imprint Melanie Burgin residence') }}</p>
            <p class="font-light">{{ __('Imprint Melanie Burgin role') }}</p>
            <p class="font-light text-gray-600">{{ __('Imprint individual signature') }}</p>
        </div>
    </div>
</x-section>

<x-section>
    <x-h2 :title="__('Disclaimer')"/>
    <p class="text-gray-800">{{ __('Imprint disclaimer') }}</p>
</x-section>
