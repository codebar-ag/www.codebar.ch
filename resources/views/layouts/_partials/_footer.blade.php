<footer class="my-16 md:my-24 text-base">
    <div class="surface-card editorial-stripe ui-region flex flex-col gap-8 p-6 md:p-10">

        <div class="hidden grid-cols-1 gap-8 md:grid lg:grid-cols-4">
            @if($configuration?->section_services)
                <div>
                    <h2 class="text-black font-semibold text-balance">{{ __('Services') }}</h2>
                    <ul class="mt-2 list-none space-y-1 text-sm">
                        @if(!empty($services) && $services->count())
                            @foreach($services as $service)
                                <li>
                                    <x-a :href="$service->url ?? localized_route('services.show',['locale' => app()->getLocale(),'service' => $service])"
                                         label="{{ $service->name }}" :target="$service->url ? '_blank' : '_self'"
                                         classAttributes="text-base"/>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endif

            @if($configuration?->section_products)
                <div>
                    <h2 class="text-black font-semibold text-balance">{{ __('Products') }}</h2>
                    <ul class="mt-2 list-none space-y-1 text-sm">
                        @if(!empty($products) && $products->count())
                            @foreach($products as $product)
                                <li>
                                    <x-a :href="$product->url ?? localized_route('products.show',['locale' => app()->getLocale(),'product' => $product])"
                                         label="{{ $product->name }}" :target="$product->url ? '_blank' : '_self'"
                                         classAttributes="text-base"/>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endif

            <div>
                <h2 class="text-black font-semibold text-balance">{{ $configuration?->company }}</h2>
                <ul class="mt-2 list-none space-y-1 text-sm">
                    <li>
                        <x-a :href="localized_route('about-us.index')" label="{{ __('About us') }}"
                             classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}"
                             classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('media.index')" label="{{ __('Media') }}"
                             classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('jobs.index')" label="{{ __('Jobs') }}" classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="route('styleguide.index')" label="Styleguide" classAttributes="text-base"/>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="text-black font-semibold text-balance">{{ __('Legal') }}</h2>
                <ul class="mt-2 list-none space-y-1 text-sm">
                    <li>
                        <x-a :href="localized_route('legal.terms.index')" label="{{ __('Terms') }}"
                             classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('legal.privacy.index')" label="{{ __('Privacy') }}"
                             classAttributes="text-base"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('legal.imprint.index')" label="{{ __('Imprint') }}"
                             classAttributes="text-base"/>
                    </li>
                </ul>
            </div>
        </div>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        @if(filled($configuration?->company))
            <div class="text-sm text-gray-500 text-center md:text-left">
                <span title="{{ app()->getLocale() }}">© {{ date('Y') }} {{ $configuration?->company }}</span>
            </div>
        @endif
    </div>
</footer>
