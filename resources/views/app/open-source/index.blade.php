<x-app-layout :page="$page">
    <x-h1 :title="__('Open Source')"/>
    <x-section>
        <x-list>
            @foreach($openSource as $entry)
                <x-list-card
                        :url="localized_route('openSource.show', ['locale' => app()->getLocale(),'openSource' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>