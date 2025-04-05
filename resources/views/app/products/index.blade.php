<x-app-layout>
    <x-h1 :title="__('Products')"/>

    <x-section>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <tbody>
                @if(!empty($products) && $products->count())
                    @foreach($products as $product)
                        <tr class="bg-white">
                            <td class="text-left py-2 border-b border-gray-100">
                                <x-a :href="$product->url ?? route('products.show',$product)" :label="$product->name"
                                     :target="$product->url ? '_blank' : '_self'"/>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </x-section>

</x-app-layout>