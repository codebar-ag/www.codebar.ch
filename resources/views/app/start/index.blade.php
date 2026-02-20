<x-app-layout :page="$page">

    <x-intro/>

    <x-section>
        <x-h1 :title="__('News')"/>
        <x-list>
            @foreach($news as $entry)
                <x-list-card
                        :url="localized_route('news.show', ['locale' => app()->getLocale(),'news' => $entry])"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-section>

    <x-docuware-showme/>

</x-app-layout>
