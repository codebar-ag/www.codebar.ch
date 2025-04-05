<footer class="mb-12 bg-white text-lg">
    <div class="flex flex-col gap-4">

        <div class="flex gap-12">
            <div class="">
                <h2 class="text-black font-semibold">{{ __('Services') }}</h2>
                <ul class="list-none text-base">

                    @if(!empty($services) && $services->count())
                        @foreach($services as $service)
                            <li>
                                <x-a :href="$service->url ?? route('services.show',$service)"
                                     label="{{ $service->name }}" :target="$service->url ? '_blank' : '_self'"
                                     classAttributes="text-lg"/>
                            </li>
                        @endforeach
                    @endif

                    <li>
                        <x-a :href="route('services.index')" label="{{ __('More') }}"
                             title="{{ __('More service information') }}"
                             classAttributes="text-base"/>
                    </li>
                </ul>
            </div>

            <div class="">
                <h2 class="text-black font-semibold">{{ __('Products') }}</h2>
                <ul class="list-none text-base">

                    @if(!empty($products) && $products->count())
                        @foreach($products as $product)
                            <li>
                                <x-a :href="$product->url ?? route('products.show',$product)"
                                     label="{{ $product->name }}" :target="$product->url ? '_blank' : '_self'"
                                     classAttributes="text-lg"/>
                            </li>
                        @endforeach
                    @endif

                    <li>
                        <x-a :href="route('products.index')" label="{{ __('More') }}"
                             title="{{ __('More product information') }}"
                             classAttributes="text-base"/>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex gap-2">

            {{--            <a href="{{ route('jobs.index') }}"
                           class="hover:underline hover:text-gray-500 transition">
                            {{ __('Jobs') }}
                        </a>
                        <span class="text-gray-500">|</span>--}}

            {{--            <a href="{{ route('media.index') }}"
                           class="hover:underline hover:text-gray-500 transition">
                            {{ __('Media') }}
                        </a>
                        <span class="text-gray-500">|</span>--}}

            {{--            <a href="{{ route('privacy.index') }}"
                           class="hover:text-black hover:font-semibold transition">
                            {{ __('Privacy') }}
                        </a>
                        <span class="text-gray-500">|</span>--}}

            {{--            <a href="{{ route('terms.index') }}"
                           class="hover:underline hover:text-gray-500 transition">
                            {{ __('Terms') }}
                        </a>

                        <span class="text-gray-500">|</span>--}}

            <a href="{{ route('imprint.index') }}"
               class="hover:text-black hover:font-semibold transition">
                {{ __('Imprint') }}
            </a>

        </div>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        <div class="text-base text-gray-500 text-center md:text-left">
            <span>© {{ date('Y') }} {{ __('paperflakes AG') }}</span>
        </div>
    </div>
</footer>
