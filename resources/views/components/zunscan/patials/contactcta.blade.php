{{-- The one blue band on a content page. It used to be a grey gradient, which
     introduced a third colour family that appeared nowhere else on the site. --}}
<x-zunscan.components.section tone="blue">
    <div class="sm:flex sm:items-center sm:justify-between sm:gap-8">
        <h2 class="text-title text-balance text-white">{{ __('zunscan.cta.heading') }}</h2>

        <a href="{{ zunscan_route('contact.index') }}"
           class="mt-6 inline-flex min-h-control w-full shrink-0 items-center justify-center gap-2 rounded-card bg-white px-6 font-bold uppercase text-zunscan-blue transition hover:bg-zunscan-white sm:mt-0 sm:w-auto">
            <span class="whitespace-nowrap">{{ __('zunscan.cta.button') }}</span>

            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
            </svg>
        </a>
    </div>
</x-zunscan.components.section>
