<footer class="border-t border-zinc-200 bg-zinc-50">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:px-8">
        {{-- Main nav grid --}}
        <div class="grid grid-cols-2 gap-x-8 gap-y-12 sm:grid-cols-3 lg:grid-cols-5">
            @if($configuration?->section_services && !empty($services) && $services->count())
                <x-ui.footer-column :title="__('Services')">
                    @foreach($services as $service)
                        <x-ui.footer-link
                            :href="$service->url ?? localized_route('services.show', ['locale' => app()->getLocale(), 'service' => $service])"
                            :target="$service->url ? '_blank' : '_self'"
                            :label="$service->name"
                        />
                    @endforeach
                </x-ui.footer-column>
            @endif

            @if($configuration?->section_products && !empty($products) && $products->count())
                <x-ui.footer-column :title="__('Products')">
                    @foreach($products as $product)
                        <x-ui.footer-link
                            :href="$product->url ?? localized_route('products.show', ['locale' => app()->getLocale(), 'product' => $product])"
                            :target="$product->url ? '_blank' : '_self'"
                            :label="$product->name"
                        />
                    @endforeach
                </x-ui.footer-column>
            @endif

            <x-ui.footer-column :title="__('Work')">
                @if($configuration?->section_open_source)
                    <x-ui.footer-link :href="localized_route('open-source.index')" :label="__('Open Source')" />
                @endif
                @if($configuration?->section_technologies)
                    <x-ui.footer-link :href="localized_route('technologies.index')" :label="__('Technologies')" />
                @endif
                @if($configuration?->section_news)
                    <x-ui.footer-link :href="localized_route('news.index')" :label="__('News')" />
                @endif
            </x-ui.footer-column>

            <x-ui.footer-column :title="__('Company')">
                <x-ui.footer-link :href="localized_route('about-us.index')" :label="__('About')" />
                @if($configuration?->section_co_working ?? true)
                    <x-ui.footer-link :href="localized_route('co-working.index')" :label="__('Co-Working')" />
                @endif
                <x-ui.footer-link :href="localized_route('jobs.index')" :label="__('Jobs')" />
                <x-ui.footer-link :href="localized_route('media.index')" :label="__('Media')" />
                <x-ui.footer-link :href="localized_route('contact.index')" :label="__('Contact')" />
            </x-ui.footer-column>

            <x-ui.footer-column :title="__('Legal')">
                <x-ui.footer-link :href="localized_route('legal.terms.index')" :label="__('Terms')" />
                <x-ui.footer-link :href="localized_route('legal.privacy.index')" :label="__('Privacy')" />
                <x-ui.footer-link :href="localized_route('legal.imprint.index')" :label="__('Imprint')" />
                <x-ui.footer-link href="{{ route('styleguide.index') }}" label="Styleguide" />
            </x-ui.footer-column>
        </div>

        {{-- Swiss Made trust strip --}}
        <div class="mt-16 flex flex-col items-start gap-6 border-t border-zinc-200 pt-8 md:flex-row md:items-center md:gap-10">
            @include('layouts._partials._footer.labels')
        </div>

        {{-- Bottom row --}}
        <div class="mt-12 flex flex-col items-start justify-between gap-4 border-t border-zinc-200 pt-8 md:flex-row md:items-center">
            <p class="text-xs text-zinc-500">
                © {{ date('Y') }} {{ $configuration?->company ?? config('site.company') }}
            </p>

            @if(filled(config('site.links.linkedin')) || filled(config('site.links.github')))
                <div class="flex items-center gap-5 text-xs text-zinc-500">
                    @if(filled(config('site.links.linkedin')))
                        <a href="{{ config('site.links.linkedin') }}" target="_blank" rel="noopener" class="hover:text-zinc-950">LinkedIn</a>
                    @endif
                    @if(filled(config('site.links.github')))
                        <a href="{{ config('site.links.github') }}" target="_blank" rel="noopener" class="hover:text-zinc-950">GitHub</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</footer>
