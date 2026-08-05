@php
    // Four cards in a 2×2 grid, and the bodies are written to roughly equal
    // length on purpose — uneven copy is what made the row look ragged.
    $solutions = [
        ['icon' => 'space',  'title' => 'zunscan.start.space_title',      'body' => 'zunscan.start.space_body'],
        // Post-Scanning-Dienst: deaktiviert, solange ePost nicht publiziert ist —
        // die Kachel bewarb eine Dienstleistung, die keine Seite mehr hat.
        // Wieder aktivieren, sobald die ePost-Seite zurückkommt (die Texte
        // liegen weiterhin unter zunscan.start.mail_* in beiden Sprachdateien).
        // ['icon' => 'mail',   'title' => 'zunscan.start.mail_title',       'body' => 'zunscan.start.mail_body'],
        ['icon' => 'shield', 'title' => 'zunscan.start.compliance_title', 'body' => 'zunscan.start.compliance_body'],
        ['icon' => 'search', 'title' => 'zunscan.start.ocr_title',        'body' => 'zunscan.start.ocr_body'],
        ['icon' => 'system', 'title' => 'zunscan.start.dms_title',        'body' => 'zunscan.start.dms_body'],
    ];
@endphp

<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.start.hero_title')" :subtitle="__('zunscan.start.subtitle')"/>

    <x-zunscan.components.section>
        <x-zunscan.components.eyebrow>{{ __('zunscan.start.solutions_eyebrow') }}</x-zunscan.components.eyebrow>
        <h2 class="mt-3 text-title text-balance text-zunscan-dark-gray">{{ __('zunscan.start.solutions_title') }}</h2>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            @foreach($solutions as $solution)
                <x-zunscan.components.card class="flex flex-col">
                    <x-zunscan.components.icon :name="$solution['icon']"/>
                    {{-- Two lines reserved for the title so the body copy starts on
                         the same baseline in all three cards — «Platzersparnis» is
                         one line, the other two wrap. --}}
                    <p class="mt-4 text-heading text-zunscan-dark-gray sm:min-h-[2lh]">{{ __($solution['title']) }}</p>
                    <p class="mt-2 flex-grow font-light text-zunscan-light-gray">{{ __($solution['body']) }}</p>

                    @isset($solution['link'])
                        <a href="{{ $solution['link'] }}"
                           class="mt-4 inline-flex min-h-control items-center gap-2 font-bold text-zunscan-blue hover:underline">
                            {{ __($solution['link_label']) }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                            </svg>
                        </a>
                    @endisset
                </x-zunscan.components.card>
            @endforeach
        </div>

        <x-zunscan.components.card class="mt-6 sm:flex sm:items-center sm:justify-between sm:gap-8">
            <div>
                <h2 class="text-title text-balance text-zunscan-dark-gray">{{ __('zunscan.start.pricing_title') }}</h2>
                <p class="mt-2 font-light text-zunscan-light-gray">{{ __('zunscan.start.pricing_body') }}</p>
            </div>

            <a href="{{ zunscan_route('services.scanning.show') }}"
               class="mt-6 inline-flex min-h-control w-full shrink-0 items-center justify-center gap-2 rounded-card bg-zunscan-light-blue px-6 font-bold text-white transition hover:bg-zunscan-blue sm:mt-0 sm:w-auto">
                <span class="whitespace-nowrap">{{ __('zunscan.nav.scanning') }}</span>

                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                </svg>
            </a>
        </x-zunscan.components.card>
    </x-zunscan.components.section>

    <x-zunscan.patials.contactcta/>
</x-zunscan.layout>
