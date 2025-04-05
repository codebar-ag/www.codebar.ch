<x-app-layout>
    <x-h1 :title="__('Imprint')"/>

    <x-section>
        <x-h2 :title="__('Company')"/>

        <address class="not-italic text-gray-700 text-lg leading-relaxed">
            <p class="font-semibold">paperflakes AG</p>
            <p>Mühlematten 12</p>
            <p>CH-4455 Zunzgen</p>
            <p>CHE-432.585.498</p>
        </address>

        <x-a-badge href="https://zefix.ch/de/search/entity/list/firm/1598166"
                   label="{{ __('Zefix') }}" class-attributes="mt-1" target="_blank"/>
    </x-section>
</x-app-layout>