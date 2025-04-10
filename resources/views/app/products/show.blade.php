<x-app-layout :page="$page">

    <x-h1 :title="$name"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-section>
        <x-content :content="$content"/>
    </x-section>

    @if(in_array('DocuWare', $tags))
        <x-docuware-showme/>
    @endif


</x-app-layout>