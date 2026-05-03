<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('News')"
        :teaser="__('Updates, announcements and insights from our team.')"
        :eyebrow="__('Journal')"
    />
    <x-ui.section class-attributes="editorial-divider">
        <x-list>
            @foreach($news as $entry)
            <x-list-card
                    :url="localized_route('news.show', ['locale' => app()->getLocale(),'news' => $entry])"
                    :title="$entry->title"
                    :teaser="$entry->teaser"
                    :tags="$entry->tags"/>
        @endforeach
        </x-list>
    </x-ui.section>
</x-app-layout>
