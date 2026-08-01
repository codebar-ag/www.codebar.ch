<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Technologies')"
            :intro="__('The tools and technologies we work with every day — deliberately chosen, and mastered in real depth over the years.')"/>
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
