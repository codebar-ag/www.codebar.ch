<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('News')"
            :intro="__('Insights from our day-to-day work: what we are building, what we are learning, and what is happening at codebar.')"/>
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
