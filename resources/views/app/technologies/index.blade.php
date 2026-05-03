<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Technologies')"
        :teaser="__('A curated field guide of technologies we actively use.')"
        :eyebrow="__('Technology Atlas')"
    />
    <x-ui.section class-attributes="editorial-divider">
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
    </x-ui.section>
</x-app-layout>