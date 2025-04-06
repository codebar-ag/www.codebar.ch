<x-app-layout>
    <x-h1 :title="__('Services')"/>

    <x-section>
        <x-list>
            @foreach($services as $service)
                <x-list-card
                        :url="route('services.show', $service)"
                        :title="$service->name"
                        :teaser="$service->teaser"
                        :tags="$service->tags"/>
            @endforeach
        </x-list>
    </x-section>

</x-app-layout>