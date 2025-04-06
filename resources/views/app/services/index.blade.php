<x-app-layout>
    <x-h1 :title="__('Services')"/>

    @foreach($services as $key => $group)
        <x-section>
            <x-h2 :title="__($key)"/>
            <x-section>
                <x-list>
                    @foreach($group as $service)
                        <x-list-card
                                :url="localized_route('services.show', $service)"
                                :title="$service->name"
                                :teaser="$service->teaser"
                                :tags="$service->tags"/>
                    @endforeach
                </x-list>
            </x-section>
        </x-section>
    @endforeach




    @include('app.services._parials.partnerships')


</x-app-layout>