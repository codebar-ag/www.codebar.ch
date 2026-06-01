<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Technology Atlas')"
        :title="__('Technologies')"
        :teaser="__('A curated field guide of technologies we actively use.')"
    />

    <x-ui.section>
        <x-ui.list>
            @foreach($technologies as $entry)
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
