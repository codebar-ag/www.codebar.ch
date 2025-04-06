<x-app-layout>

    <x-section>
        <x-h1 :title="$name"/>
        <x-h1-teaser :teaser="$teaser"/>

        <x-content :content="$content"/>
    </x-section>

</x-app-layout>