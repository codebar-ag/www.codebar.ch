{{-- The same PageNavigation list as the desktop menu, so the two can never say
     different things. The alternating gray stripes are gone: they had drifted out
     of sequence and read as an accident. One quiet surface with dividers instead,
     and the current page carries the brand tint. --}}
<div x-show="open" x-transition.opacity x-cloak x-trap.inert.noscroll="open"
     id="mobile-navigation" role="dialog" aria-modal="true" aria-label="{{ __('Menu') }}"
     class="fixed inset-0 z-50 overflow-y-auto bg-white">

    <div class="mx-auto max-w-frame px-4 pt-12 pb-8 sm:px-6 lg:px-8">
        <div class="flex justify-end">
            <button @click="close" type="button"
                    class="-mr-2 grid size-control place-items-center rounded-pill transition hover:text-brand focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                <span class="sr-only">{{ __('Close menu') }}</span>
                <x-icon.close class="size-7" stroke-width="1.5"/>
            </button>
        </div>

        <div class="mt-4 overflow-hidden rounded-panel border border-border-soft">
            <div class="flex flex-col divide-y divide-border-soft">
                @foreach(\App\Support\PageNavigation::pages() as $item)
                    <x-nav.link :route="$item['route']" :label="$item['label']" variant="mobile"/>
                @endforeach
            </div>

            <div class="flex flex-col divide-y divide-border-soft border-t border-border-soft bg-surface">
                <a href="tel:{{ config('company.phone.e164') }}" title="{{ __('Contact Phone number') }}"
                   class="flex min-h-control items-center justify-center px-4 text-base text-muted transition hover:text-brand focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand">
                    {{ config('company.phone.display') }}
                </a>
                <a href="mailto:{{ config('company.email') }}" title="{{ __('Contact email address') }}"
                   class="flex min-h-control items-center justify-center px-4 text-base text-muted transition hover:text-brand focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand">
                    {{ config('company.email') }}
                </a>
            </div>
        </div>

        @if(! empty($locales))
            <div class="mt-4 flex flex-col items-center gap-1">
                <span class="text-sm text-muted">{{ __('Language') }}</span>
                <x-nav.locale-switch :locales="$locales" :separator="false" class="gap-4"/>
            </div>
        @endif
    </div>
</div>
