<x-app-layout :page="$page">
    <x-h1 :title="__('Technologies')"/>
    <x-section>
        <x-list>
            @foreach($technologies as $entry)
                <x-list-card
                        :url="localized_route('technologies.show', ['locale' => app()->getLocale(),'technology' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>