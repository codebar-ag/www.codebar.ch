<x-app-layout :page="$page">
    <x-blocks.editorial-hero :title="$name" :teaser="$teaser" :eyebrow="__('Technology')" />

    <x-ui.section>
        <x-blocks.content-river>
            <x-content :content="$content"/>
        </x-blocks.content-river>
    </x-ui.section>

</x-app-layout>