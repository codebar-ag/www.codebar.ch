<x-app-layout>
    <x-h1 :title="__('News')"/>

    <x-section>
        <x-list>
            @foreach($news as $entry)
                <x-list-card
                        :url="localized_route('news.show', $entry)"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>