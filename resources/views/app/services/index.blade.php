<x-app-layout :page="$page">
    <x-h1 :title="__('Services')"/>

    @foreach($services as $key => $group)
        <x-section>
            <x-h2 :title="__($key)"/>
            <x-section>
                <x-list>
                    @foreach($group as $service)
                        <x-list-card
                                :url="$service->url ?? localized_route('services.show',$service)"
                                :title="$service->name"
                                :teaser="$service->teaser"
                                :tags="$service->tags"
                                target="{{ $service->url ? '_blank' : '_self' }}"/>
                    @endforeach
                </x-list>
            </x-section>
        </x-section>
    @endforeach




    @include('app.services._parials.partnerships')


</x-app-layout>