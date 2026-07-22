<x-app-layout :page="$page">
    <x-h1 :title="__('Technologies')"/>
    <x-layout.section>
        <x-layout.list>
            @foreach($technologies as $entry)
                <x-card.item-card
                        :url="$entry->link"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        target="_blank"
                        :level="2"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>
</x-app-layout>
