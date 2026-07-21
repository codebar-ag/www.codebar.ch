<x-app-layout :page="$page">
    <x-h1 :title="__('Products')"/>
    <x-section>
        <x-list>
            @foreach($products as $entry)
                <x-product-card
                        :url="localized_route('products.show', ['locale' => app()->getLocale(),'product' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-section>
</x-app-layout>