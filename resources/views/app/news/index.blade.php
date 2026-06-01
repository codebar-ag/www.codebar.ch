<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Journal')"
        :title="__('News')"
        :teaser="__('Updates, announcements and insights from our team.')"
    />

    <x-ui.section>
        <x-ui.list>
            @foreach($news as $entry)
                <x-list-card
                    :url="localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry])"
                    :title="$entry->title"
                    :teaser="$entry->teaser"
                    :tags="$entry->tags"
                />
            @endforeach
        </x-ui.list>
    </x-ui.section>
</x-app-layout>
