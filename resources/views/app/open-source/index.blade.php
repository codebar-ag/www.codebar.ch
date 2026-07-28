<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Open Source')"
            :intro="__('We build on open source — and give back. These are the projects and contributions we share with the community.')"/>
    <x-layout.section>
        <x-layout.list>
            @foreach($openSource as $entry)
                {{-- Entries with a written body get an internal detail page; the
                     rest link straight to GitHub, because there is nothing here
                     to show beyond what the repository already says. --}}
                <x-card.item-card
                        :url="$entry->hasWrittenContent()
                            ? localized_route('open-source.show', ['locale' => app()->getLocale(), 'openSource' => $entry])
                            : $entry->link"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        :target="$entry->hasWrittenContent() ? '_self' : '_blank'"
                        :level="2"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>
</x-app-layout>
