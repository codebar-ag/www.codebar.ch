<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Open Source')"
        :teaser="__('Contributions and tools we share with the community.')"
        :eyebrow="__('Community')"
    />
    <x-ui.section class-attributes="editorial-divider">
        <x-list>
            @foreach($openSource as $entry)
                <x-list-card
                        :url="$entry->link"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        target="_blank"/>
            @endforeach
        </x-list>
    </x-ui.section>
</x-app-layout>