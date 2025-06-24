<x-app-layout :page="$page">
    <x-h1 :title="__('Technologies')"/>
    <x-section>
        <x-list>
            @foreach($technologies as $entry)
                <x-list-card
                        :url="$entry->link"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        target="_blank"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>