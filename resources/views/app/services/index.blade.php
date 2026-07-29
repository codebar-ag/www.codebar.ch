<x-app-layout :page="$page" :schema="$schema">
    <x-layout.page-header :title="__('Services')" :intro="__('components.services.header')"/>

    <x-layout.section>
        <x-layout.list>
            @foreach($services as $entry)
                <x-card.item-card :title="$entry->name" :teaser="$entry->teaser" :tags="$entry->tags" :level="2"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>

</x-app-layout>
