<div
        x-show="navigation"
        x-transition
        @click.outside="navigation = false"
        class="md:hidden mt-4 text-xl space-y-2"
>
    <!-- News -->
    <a @click.stop
       href="{{ route('start.index') }}"
       title="{{ __('News') }}"
       class="block py-3 text-center bg-gray-50/50 hover:text-black hover:font-semibold transition rounded-t-lg">
        {{ __('News') }}
    </a>

{{--    <!-- About us -->
    <a @click.stop
       href="{{ route('about-us.index') }}"
       title="{{ __('About us') }}"
       class="block py-3 text-center bg-gray-50 hover:text-black hover:font-semibold transition">
        {{ __('About us') }}
    </a>--}}

    <!-- Services -->
    <div @click.stop class="py-3 text-center bg-gray-50/50 transition space-y-1">
        <span>{{ __('Services') }}</span>
        <div class="mt-1 text-sm text-gray-600 space-y-1">

            @if(!empty($services) && $services->count())
                @foreach($services as $service)
                    <a target="{{ $service->url ? '_blank' : '_self' }}"
                       href="{{ $service->url ?? route('services.show',$service) }}"
                       title="{{ $service->name }}"
                       class="block text-base hover:text-black hover:font-semibold transition">
                        {{ $service->name }}
                    </a>
                @endforeach
            @endif

            <a href="{{ route('services.index') }}"
               title="{{ __('More service information') }}"
               class="block text-sm hover:text-black hover:font-semibold transition">
                {{ __('More') }}
            </a>
        </div>
    </div>

    <!-- Products -->
    <div @click.stop class="py-3 text-center bg-gray-50 transition space-y-1">
        <span>{{ __('Products') }}</span>
        <div class="mt-1 text-sm text-gray-600 space-y-1">

            @if(!empty($products) && $products->count())
                @foreach($products as $product)
                    <a target="{{ $product->url ? '_blank' : '_self' }}"
                       href="{{ $product->url ?? route('products.show',$product) }}"
                       title="{{ $product->name }}"
                       class="block text-base hover:text-black hover:font-semibold transition">
                        {{ $product->name }}
                    </a>
                @endforeach
            @endif

            <a href="{{ route('products.index') }}"
               title="{{ __('More product information') }}"
               class="block text-sm hover:text-black hover:font-semibold transition">
                {{ __('More') }}
            </a>
        </div>
    </div>

    <!-- Contact -->
    <div @click.stop class="py-3 text-center bg-gray-50/50 transition space-y-1">
        <span>{{ __('Contact') }}</span>
        <div class="mt-1 text-sm text-gray-600 space-y-1">
            <a href="tel:0041615156090"
               title="{{ __('Contact Phone number') }}"
               class="block text-base hover:text-black hover:font-semibold transition">
                +41 61 515 60 90
            </a>
            <a href="mailto:info@paperflakes.ch"
               title="{{ __('Contact email address') }}"
               class="block text-base hover:text-black hover:font-semibold transition">
                info@paperflakes.ch
            </a>
            <a href="{{ route('contact.index') }}"
               title="{{ __('More contact information') }}"
               class="block text-sm hover:text-black hover:font-semibold transition">
                {{ __('More') }}
            </a>
        </div>
    </div>

    {{-- <!-- Language -->
     <div @click.stop class="py-3 text-center bg-gray-50/50 transition space-y-1">
         <span>{{ __('Language') }}</span>
         <div class="mt-1 flex justify-center gap-4 text-sm text-gray-600">
             <a href="{{ route('locale.update', 'de') }}"
                title="{{ __('Update to german language') }}"
                class="text-base hover:text-black hover:font-semibold transition">
                 {{ __('DE') }}
             </a>
             <a href="{{ route('locale.update', 'en') }}"
                title="{{ __('Update to english language') }}"
                class="text-base hover:text-black hover:font-semibold transition">
                 {{ __('EN') }}
             </a>
         </div>
     </div>--}}

</div>