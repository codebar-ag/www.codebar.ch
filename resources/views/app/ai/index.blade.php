<x-app-layout :page="$page">
    <x-h1 :title="__('AI')"/>

    <x-section>
        <x-list>
            @foreach($services as $service)
                <x-list-card
                        :url="localized_route('ai.show', ['slug' => $service['slug']])"
                        :title="$service['name']"
                        :teaser="$service['teaser']"
                        :tags="[]"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>
