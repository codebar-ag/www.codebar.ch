<header x-data="{ mobileMenu: false }" x-cloak>
    <nav class="mx-auto flex items-center justify-between py-6">
        <div class="flex md:flex-1">
            <a href="{{ zunscan_route('start.index') }}">
                <span class="sr-only">zunscan.ch</span>
                <img class="h-16 w-auto" src="{{ asset('images/zunscan/zunscan_logo_pos.svg') }}" alt="">
            </a>
        </div>
        <div class="flex md:hidden">
            <button x-on:click="mobileMenu = !mobileMenu" type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-white">
                <span class="sr-only">{{ __('zunscan.nav.open_menu') }}</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
        </div>
        <div class="hidden md:flex md:items-center md:gap-x-8">
            <a href="{{ zunscan_route('start.index') }}"
               class="rounded p-2 text-lg font-semibold uppercase leading-6 text-white transition hover:bg-white/10">{{ __('zunscan.nav.start') }}</a>

            <a href="{{ zunscan_route('services.scanning.show') }}"
               class="rounded p-2 text-lg font-semibold uppercase leading-6 text-white transition hover:bg-white/10">{{ __('zunscan.nav.scanning') }}</a>

            <a href="{{ zunscan_route('about.index') }}"
               class="rounded p-2 text-lg font-semibold uppercase leading-6 text-white transition hover:bg-white/10">{{ __('zunscan.nav.about') }}</a>
            <a href="{{ zunscan_route('contact.index') }}"
               class="rounded p-2 text-lg font-semibold uppercase leading-6 text-white transition hover:bg-white/10">{{ __('zunscan.nav.contact') }}</a>

            <x-zunscan.patials.locale-switch/>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div x-show="mobileMenu" class="md:hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-50"></div>
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <a href="{{ zunscan_route('start.index') }}" class="-m-1.5 p-1.5">
                    <span class="sr-only">zunscan.ch</span>
                    <img class="h-16 w-auto" src="{{ asset('images/zunscan/zunscan_logo_pos.svg') }}" alt="">
                </a>
                <button x-on:click="mobileMenu = !mobileMenu" type="button"
                        class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">{{ __('zunscan.nav.close_menu') }}</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                        <a href="{{ zunscan_route('start.index') }}"
                           class="-mx-3 flex min-h-control items-center rounded px-3 text-base font-semibold text-zunscan-dark-gray uppercase hover:bg-gray-50">{{ __('zunscan.nav.start') }}</a>
                        <a href="{{ zunscan_route('services.scanning.show') }}"
                           class="-mx-3 flex min-h-control items-center rounded px-3 text-base font-semibold text-zunscan-dark-gray uppercase hover:bg-gray-50">{{ __('zunscan.nav.scanning') }}</a>
                        <a href="{{ zunscan_route('about.index') }}"
                           class="-mx-3 flex min-h-control items-center rounded px-3 text-base font-semibold text-zunscan-dark-gray uppercase hover:bg-gray-50">{{ __('zunscan.nav.about') }}</a>
                        <a href="{{ zunscan_route('contact.index') }}"
                           class="-mx-3 flex min-h-control items-center rounded px-3 text-base font-semibold text-zunscan-dark-gray uppercase hover:bg-gray-50">{{ __('zunscan.nav.contact') }}</a>

                        <x-zunscan.patials.locale-switch class="mx-3 mt-4"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
