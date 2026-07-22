<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Open Source')"
            :intro="__('We build on open source — and give back. These are the projects and contributions we share with the community.')"/>
    <x-layout.section>
        <x-layout.list>
            @foreach($openSource as $entry)
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
