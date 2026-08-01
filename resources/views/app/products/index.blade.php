<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Products')"
            :intro="__('Products born out of real project work: developed by us, in daily use, and continuously improved over the years.')"/>
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
