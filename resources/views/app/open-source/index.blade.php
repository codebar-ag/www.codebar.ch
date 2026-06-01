<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Community')"
        :title="__('Open Source')"
        :teaser="__('Contributions and tools we share with the community.')"
    />

    <x-ui.section>
        <x-ui.list>
            @foreach($openSource as $entry)
                <x-list-card
                    :url="$entry->link"
                    :title="$entry->title"
                    :teaser="$entry->teaser"
                    :tags="$entry->tags"
                    target="_blank"
                />
            @endforeach
        </x-ui.list>
    </x-ui.section>
</x-app-layout>
