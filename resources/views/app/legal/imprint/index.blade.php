<x-app-layout :page="$page">
    <x-h1 :title="__('Imprint')"/>

    <x-section>
        <x-h2 :title="__('Company')"/>

        <address class="not-italic text-gray-700 text-lg leading-relaxed">
            <p class="font-semibold">paperflakes AG</p>
            <p>Mühlematten 12</p>
            <p>CH-4455 Zunzgen</p>
            <p>CHE-432.585.498</p>
        </address>

        <x-a-badge href="https://zefix.ch/de/search/entity/list/firm/1598166" label="{{ __('Zefix') }}"
                   class-attributes="mt-1" target="_blank" rel="noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="ml-1 size-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
        </x-a-badge>
    </x-section>
</x-app-layout>