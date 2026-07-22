<x-app-layout :page="$page">
    <x-h1 :title="__('News')"/>
    <x-layout.section>
        <x-layout.list>
            @foreach($news as $entry)
                <x-card.item-card
                        :url="localized_route('news.show', ['locale' => app()->getLocale(),'news' => $entry])"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        :level="2"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>
</x-app-layout>
