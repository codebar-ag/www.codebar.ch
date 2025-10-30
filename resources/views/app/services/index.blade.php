<x-app-layout :page="$page">
    <x-h1 :title="__('Services')"/>

    @foreach($services as $key => $group)
        <x-section>
            <x-h2 :title="__($key)"/>
            <x-section>
                <x-list>
                    @foreach($group as $entry)
                        <x-list-card
                                :url="$entry->url ?? localized_route('services.show',['locale' => app()->getLocale(),'service' => $entry])"
                                :title="$entry->name"
                                :teaser="$entry->teaser"
                                :tags="$entry->tags"
                                target="{{ $entry->url ? '_blank' : '_self' }}"/>
                    @endforeach
                </x-list>
            </x-section>
        </x-section>
    @endforeach

    @include('app.services._parials.partnerships')


</x-app-layout>
