<x-app-layout :page="$page">
    <x-blocks.editorial-hero :title="$name" :teaser="$teaser" :eyebrow="__('Product')" />

    <x-ui.section>
        <x-blocks.content-river>
            <x-content :content="$content"/>
        </x-blocks.content-river>
    </x-ui.section>

    @if(collect(['DMS/ECM', 'DocuWare'])->diff($tags)->isEmpty())
        <x-docuware-showme/>
    @endif

</x-app-layout>