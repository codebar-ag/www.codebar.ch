<div x-show="open" x-transition.opacity x-cloak x-trap.inert.noscroll="open"
    id="mobile-navigation" role="dialog" aria-modal="true" aria-label="{{ __('Menu') }}"
    class="fixed inset-0 z-50 overflow-y-auto bg-white">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-12 pb-8">
        <div class="px-4 md:px-0">

            <div class="flex justify-end">
                <button @click="close" type="button"
                    class="p-2 -mr-2 hover:text-brand transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand rounded">
                    <span class="sr-only">{{ __('Close menu') }}</span>
                    <x-icon.close class="size-7" stroke-width="1.5"/>
                </button>
            </div>

        <div class="mt-4 text-xl space-y-2">
            <!-- Home -->
            <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}"
                class="block py-3 text-center bg-gray-50/50 hover:text-brand hover:font-semibold transition rounded-t-lg">
                {{ __('Home') }}
            </a>

            <!-- Services -->
            <a href="{{ localized_route('services.index') }}" title="{{ __('Services') }}"
                class="block py-3 text-center bg-gray-50 hover:text-brand hover:font-semibold transition">
                {{ __('Services') }}
            </a>

            <!-- Team -->
            <a href="{{ localized_route('about-us.index') }}" title="{{ __('Team') }}"
                class="block py-3 text-center bg-gray-50/50 hover:text-brand hover:font-semibold transition">
                {{ __('Team') }}
            </a>

            <!-- News -->
            <a href="{{ localized_route('news.index') }}" title="{{ __('News') }}"
                class="block py-3 text-center bg-gray-50 hover:text-brand hover:font-semibold transition">
                {{ __('News') }}
            </a>

            <!-- AI -->
            <a href="{{ localized_route('ai.index') }}" title="{{ __('AI') }}"
                class="block py-3 text-center bg-gray-50 hover:text-brand hover:font-semibold transition">
                {{ __('AI') }}
            </a>

            <!-- Network -->
            <a href="{{ localized_route('network.index') }}" title="{{ __('Network') }}"
                class="block py-3 text-center bg-gray-50/50 hover:text-brand hover:font-semibold transition">
                {{ __('Network') }}
            </a>

            <!-- Contact -->
            <div class="py-3 text-center bg-gray-50/25 transition space-y-1">
                <a href="{{ localized_route('contact.index') }}" title="{{ __('Contact') }}"
                    class="block text-center bg-gray-50/50 hover:text-brand hover:font-semibold transition rounded-t-lg">
                    {{ __('Contact') }}
                </a>
                <div class="mt-1 text-sm text-gray-600 space-y-1">
                    <a href="tel:{{ config('company.phone.e164') }}" title="{{ __('Contact Phone number') }}"
                        class="block text-base hover:text-brand hover:font-semibold transition">
                        {{ config('company.phone.display') }}
                    </a>
                    <a href="mailto:{{ config('company.email') }}" title="{{ __('Contact email address') }}"
                        class="block text-base hover:text-brand hover:font-semibold transition">
                        info@codebar.ch
                    </a>
                </div>
            </div>

            <!-- Language -->
            @if (!empty($locales))
                <div class="py-3 text-center bg-gray-50/50 transition space-y-1">
                    <span>{{ __('Language') }}</span>
                    <div class="mt-1 flex justify-center gap-4 text-sm text-gray-600">
                        @foreach ($locales as $language)
                            <a href="{{ locale_switch_url($language->value) }}"
                                hreflang="{{ str_replace('_', '-', $language->value) }}"
                                @if($language->value === app()->getLocale()) aria-current="true" @endif
                                class="text-base hover:text-brand hover:font-semibold transition"
                                title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                                {{ $language->getLabel() }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        </div>
    </div>
</div>
