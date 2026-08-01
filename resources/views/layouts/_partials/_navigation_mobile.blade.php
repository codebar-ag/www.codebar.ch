<div x-show="open" x-transition.opacity x-cloak x-trap.inert.noscroll="open"
     id="mobile-navigation" role="dialog" aria-modal="true" aria-label="{{ __('Menu') }}"
     class="fixed inset-0 z-50 overflow-y-auto bg-white">

    <div class="mx-auto w-full max-w-frame px-4 sm:px-6 lg:px-8">
        <div class="px-4 pt-12 pb-12">
            <div class="flex items-center justify-between">
                <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}"
                   class="group inline-block max-w-1/2 rounded-pill focus-ring">
                    @include('layouts._logos._codebar')
                </a>

                <button @click="close" type="button"
                        class="-mr-2 icon-button transition hover:text-brand focus-ring">
                    <span class="sr-only">{{ __('Close menu') }}</span>
                    <x-icon.close class="size-7" stroke-width="1.5"/>
                </button>
            </div>

            <div class="mt-10 flex flex-col">
                @foreach(\App\Support\PageNavigation::pages() as $item)
                    <x-nav.link :route="$item['route']" :label="$item['label']" variant="mobile"/>
                @endforeach
            </div>

            <div class="mt-10 flex flex-col border-t border-border-soft pt-6 text-base text-muted">
                <a href="tel:{{ config('company.phone.e164') }}" title="{{ __('Contact Phone number') }}"
                   class="flex min-h-control items-center rounded-pill transition hover:text-brand focus-ring">
                    {{ config('company.phone.display') }}
                </a>
                <a href="mailto:{{ config('company.email') }}" title="{{ __('Contact email address') }}"
                   class="flex min-h-control items-center rounded-pill transition hover:text-brand focus-ring">
                    {{ config('company.email') }}
                </a>
            </div>

            @if(! empty($locales))
                <div class="mt-6 flex items-center gap-3">
                    <span class="text-base text-muted">{{ __('Language') }}</span>
                    <x-nav.locale-switch :locales="$locales" class="text-lg"/>
                </div>
            @endif
        </div>
    </div>
</div>
