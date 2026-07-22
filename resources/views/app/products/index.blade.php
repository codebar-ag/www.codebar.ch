<x-app-layout :page="$page">
    <x-h1 :title="__('Products')"/>
    <x-layout.section>
        <x-layout.list>
            @foreach($products as $entry)
                <x-card.item-card
                        :url="localized_route('products.show', ['locale' => app()->getLocale(),'product' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        :level="2"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>
</x-app-layout>
