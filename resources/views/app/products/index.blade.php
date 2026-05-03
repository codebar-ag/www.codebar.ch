<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Products')"
        :teaser="__('Selected platforms and products with practical impact.')"
        :eyebrow="__('Product Studio')"
    />
    <x-ui.section class-attributes="editorial-divider">
        <x-list>
            @foreach($products as $entry)
                <x-list-card
                        :url="localized_route('products.show', ['locale' => app()->getLocale(),'product' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"/>
            @endforeach
        </x-list>
    </x-ui.section>
</x-app-layout>