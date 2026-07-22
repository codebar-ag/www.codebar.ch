<x-app-layout :page="$page">
    <x-h1 :title="__('Services')"/>

    @foreach($services as $key => $group)
        <x-layout.section>
            <x-h2 :title="__($key)"/>
            <x-layout.list>
                @foreach($group as $entry)
                    <x-card.item-card
                            :url="$entry->url ?? localized_route('services.show',['locale' => app()->getLocale(),'service' => $entry])"
                            :title="$entry->name"
                            :teaser="$entry->teaser"
                            :tags="$entry->tags"
                            target="{{ $entry->url ? '_blank' : '_self' }}"/>
                @endforeach
            </x-layout.list>
        </x-layout.section>
    @endforeach

    @include('app.services._partials.partnerships')

</x-app-layout>
