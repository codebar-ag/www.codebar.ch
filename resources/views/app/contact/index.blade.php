<x-app-layout>
    <x-h1 :title="__('Contact')"/>

    <x-section-flex>
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-a href="tel:0041615156095" label="{{ __('+41 61 515 60 95') }}" classAttributes="block"/>
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
                   label="{{ __('Google Maps') }}" class-attributes="mt-1" target="_blank"/>
    </x-section>

</x-app-layout>