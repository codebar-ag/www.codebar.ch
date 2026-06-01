<x-app-layout :page="$page">
    <x-ui.hero :eyebrow="__('Service')" :title="$name" :teaser="$teaser" />

    <x-content :content="$content" />

    @if(collect(['DMS/ECM', 'DocuWare'])->diff($tags)->isEmpty())
        <x-docuware-showme />
    @endif
</x-app-layout>
