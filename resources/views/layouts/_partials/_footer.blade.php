<footer class="mb-12 bg-white text-lg">
    <div class="flex flex-col gap-4">

        <div class="flex gap-12">
            <div class="">
                <h2 class="text-black font-semibold">{{ __('Services') }}</h2>
                <ul class="list-none text-base">

                    @if(!empty($services) && $services->count())
                        @foreach($services as $service)
                            <li>
                                <a target="{{ $service->url ? '_blank' : '_self' }}"
                                   href="{{ $service->url ? '_blank' : '_self'  }}" title="{{ $service->name }}"
                                   class="hover:text-black hover:font-semibold transition">{{ $service->name }}</a>
                            </li>
                        @endforeach
                    @endif

                    <li>
                        <a href="{{ route('services.index') }}" title="{{ __('More service information') }}"
                           class="hover:text-black hover:font-semibold transition">{{ 'More' }}</a>
                    </li>
                </ul>
            </div>

            <div class="">
                <h2 class="text-black font-semibold">{{ __('Products') }}</h2>
                <ul class="list-none text-base">

                    @if(!empty($products) && $products->count())
                        @foreach($products as $product)
                            <li>
                                <a target="{{ $product->url ? '_blank' : '_self' }}"
                                   href="{{ $product->url ? '_blank' : '_self'  }}" title="{{ $product->name }}"
                                   class="hover:text-black hover:font-semibold transition">{{ $product->name }}</a>
                            </li>
                        @endforeach
                    @endif

                    <li>
                        <a href="{{ route('products.index') }}" title="{{ __('More product information') }}"
                           class="hover:text-black hover:font-semibold transition">{{ 'More' }}</a>
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
               class="hover:underline hover:text-gray-500 transition">
                {{ __('Imprint') }}
            </a>

        </div>
        <div class="text-base text-gray-500">
            <span>© {{ date('Y') }} {{ __(':company, All rights reserved.', ['company' => 'paperflakes AG']) }}</span>
        </div>
    </div>
</footer>
