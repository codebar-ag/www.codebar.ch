<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Product Studio')"
        :title="__('Products')"
        :teaser="__('Selected platforms and products with practical impact.')"
    />

    <x-ui.section>
        <x-ui.list>
            @foreach($products as $entry)
                <x-list-card
                    :url="localized_route('products.show', ['locale' => app()->getLocale(), 'product' => $entry])"
                    :title="$entry->name"
                    :teaser="$entry->teaser"
                    :tags="$entry->tags"
                />
            @endforeach
        </x-ui.list>
    </x-ui.section>
</x-app-layout>
