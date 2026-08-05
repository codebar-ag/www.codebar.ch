@php
    // Both owners, linked, joined with an ampersand. Built here rather than
    // baked into the translation so the string stays one natural sentence and
    // the company list has a single source in config.
    $owners = collect(config('zunscan.people'))
        ->map(fn (array $person): string => sprintf(
            '<a href="%s" target="_blank" rel="noopener" class="font-bold hover:underline">%s</a>',
            e($person['website']),
            e($person['company']),
        ))
        ->implode(' &amp; ');
@endphp

{{-- Same dark textured surface as the header, so the page bookends:
     dark header → paper content → one bright blue CTA band → dark footer.
     The footer used to carry the identical gradient as the CTA directly above
     it, which made the boundary between them read as a rendering seam. --}}
<footer class="bg-paper-dark" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">{{ __('zunscan.footer.heading') }}</h2>

    <div class="mx-auto max-w-5xl px-6 py-section">
        <div class="grid gap-10 sm:grid-cols-2">
            <div>
                <p class="text-eyebrow uppercase text-white/70">{{ __('zunscan.footer.nav_title') }}</p>
                <ul role="list" class="mt-4 space-y-3">
                    <li>
                        <a href="{{ zunscan_route('start.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.home') }}</a>
                    </li>
                    <li>
                        <a href="{{ zunscan_route('services.scanning.show') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.scanning') }}</a>
                    </li>
                    <li>
                        <a href="{{ zunscan_route('about.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.about') }}</a>
                    </li>
                    <li>
                        <a href="{{ zunscan_route('contact.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.contact') }}</a>
                    </li>
                </ul>
            </div>

            <div>
                <p class="text-eyebrow uppercase text-white/70">{{ __('zunscan.footer.legal_title') }}</p>
                <ul role="list" class="mt-4 space-y-3">
                    <li>
                        <a href="{{ zunscan_route('media.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.media') }}</a>
                    </li>
                    <li>
                        <a href="{{ zunscan_route('terms.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.imprint') }}</a>
                    </li>
                    <li>
                        <a href="{{ zunscan_route('privacy.index') }}"
                           class="font-bold uppercase leading-6 text-white hover:text-gray-200">{{ __('zunscan.nav.privacy') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- One bottom bar instead of the two stacked half-rows there used to be:
             attribution takes the slot LinkedIn had, the swiss-digital-services
             mark moves up beside it. On a phone the two stack and centre. --}}
        <div class="mt-12 flex flex-col items-center gap-6 border-t border-white/20 pt-8 sm:flex-row sm:justify-between">
            <p class="order-2 text-balance text-center leading-6 text-white sm:order-1 sm:text-left">
                {!! __('zunscan.footer.copyright', ['year' => now()->year, 'companies' => $owners]) !!}
            </p>

            {{-- On a white chip rather than CSS-inverted. The mark only exists as
                 black artwork, and its plus and gear are white cut-outs rather
                 than transparency — brightness(0) invert(1) flattens them into
                 the silhouette and the folder loses its inner drawing. --}}
            <a class="order-1 shrink-0 rounded-card bg-white px-4 py-3 sm:order-2" target="_blank" rel="noopener"
               href="https://www.swissmadesoftware.org/about/swiss-digital-services.html">
                <img class="max-w-[140px]"
                     src="{{ asset('images/zunscan/sms-logo-v-black-services.png') }}"
                     loading="lazy" decoding="async"
                     alt="{{ __('zunscan.footer.swiss_digital_services') }}">
            </a>
        </div>
    </div>
</footer>
