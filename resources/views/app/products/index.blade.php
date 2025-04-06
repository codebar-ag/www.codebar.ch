<x-app-layout>
    <x-h1 :title="__('Products')"/>

    <x-section>
        <x-list>
            @foreach($products as $product)
                <x-list-card
                        :url="localized_route('products.show', $product)"
                        :title="$product->name"
                        :teaser="$product->teaser"
                        :tags="$product->tags"/>
            @endforeach
        </x-list>
    </x-section>

</x-app-layout>