<x-app-layout :page="$page">
    <x-h1 :title="__('Contact')"/>

    <x-section-flex>
        <div>
            <x-h2 :title="__('Phone')"/>
            <x-a href="tel:0041615156090" label="{{ __('+41 61 515 60 90') }}" classAttributes="block"/>
        </div>
        <div>
            <x-h2 :title="__('Email')"/>
            <x-a href="mailto:info@paperflakes.ch?subject=Hello%20World!" label="{{ __('info(at)paperflakes.ch') }}"
                 classAttributes="block"/>
        </div>
    </x-section-flex>

    <x-section>
        <x-h2 :title="__('Office')"/>
        <div class="grid grid-cols-2">
            <div class="">
                <address class="not-italic leading-relaxed ">
                    <p class="font-semibold">paperflakes AG</p>
                    <p class="italic">{{ __('Headquarter') }}</p>
                    <p class="font-light">Haupstrasse 91</p>
                    <p class="font-light">CH-4455 Zunzgen</p>
                </address>
                <x-a-badge href="https://www.google.com/maps/place/Hauptstrasse+91,+4455+Zunzgen,+Schweiz"
                           label="{{ __('Google Maps') }}" class-attributes="mt-1" target="_blank"
                           rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="ml-1 size-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                </x-a-badge>
            </div>
            <div class="">
                <address class="not-italic leading-relaxed ">
                    <p class="font-semibold">paperflakes AG</p>
                    <p class="italic">{{__('Branch office')}}</p>
                    <p class="font-light">Langegasse 39</p>
                    <p class="font-light">CH-4104 Oberwil</p>
                </address>
                <x-badge label="{{ __('Oktober 2025') }}" class-attributes="mt-1" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="text-gray-400 ml-1 size-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                </x-badge>
            </div>
        </div>
    </x-section>

</x-app-layout>