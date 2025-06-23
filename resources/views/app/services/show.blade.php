<x-app-layout :page="$page">

    <x-h1 :title="$name"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-section>
        <x-content :content="$content"/>
    </x-section>

    @if(collect(['DMS/ECM', 'DocuWare'])->diff($tags)->isEmpty())
        <x-docuware-showme/>
    @endif

</x-app-layout>