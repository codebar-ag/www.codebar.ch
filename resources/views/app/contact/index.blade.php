<x-app-layout>
    <x-h1 :title="__('Contact')"/>

    <x-section-flex>
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-a href="tel:0041615156095" label="{{ __('+41 61 515 60 952') }}" classAttributes="block"/>
        </div>
        <div>
            <x-h2 :title="__('Email')"/>
            <x-a href="mailto:info@paperflakes.ch?subject=Hello%20World!" label="{{ __('info(at)paperflakes.ch') }}"
                 classAttributes="block"/>
        </div>
    </x-section-flex>

    <x-section>
        <x-h2 :title="__('Office')"/>
        <address class="not-italic leading-relaxed ">
            <p class="font-semibold">paperflakes AG</p>
            <p>Haupstrasse 91</p>
            <p>CH-4455 Zunzgen</p>
        </address>
        <x-a-badge href="https://www.google.com/maps/place/Hauptstrasse+91,+4455+Zunzgen,+Schweiz"
                   label="{{ __('Google Maps') }}" class-attributes="mt-1" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="mr-1 size-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
        </x-a-badge>
    </x-section>

</x-app-layout>