@php
    $work = [
        ['icon' => 'space',  'title' => 'zunscan.about.what_title', 'body' => 'zunscan.about.what_body'],
        ['icon' => 'shield', 'title' => 'zunscan.about.how_title',  'body' => 'zunscan.about.how_body'],
    ];

    // Company and link come from the same config the contact page uses, so the
    // two never drift apart; only the blurb is translated, keyed by company.
    $partners = config('zunscan.people');
@endphp

<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.about.title')" :subtitle="__('zunscan.about.subtitle')"/>

    <x-zunscan.components.section>
        {{-- What we do comes first, who we are second: the eyebrow used to
             promise «who is behind zunscan.ch» and then open with what we do. --}}
        <x-zunscan.components.eyebrow>{{ __('zunscan.about.work_eyebrow') }}</x-zunscan.components.eyebrow>
        <h2 class="mt-3 text-title text-balance text-zunscan-dark-gray">{{ __('zunscan.about.what_we_do_title') }}</h2>
        <p class="mt-4 max-w-3xl text-lead font-light text-zunscan-light-gray">{{ __('zunscan.about.lead') }}</p>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            @foreach($work as $block)
                <x-zunscan.components.card>
                    <x-zunscan.components.icon :name="$block['icon']"/>
                    <p class="mt-4 text-heading text-zunscan-dark-gray">{{ __($block['title']) }}</p>
                    <p class="mt-2 font-light text-zunscan-light-gray">{{ __($block['body']) }}</p>
                </x-zunscan.components.card>
            @endforeach
        </div>

        <div class="mt-16">
            <x-zunscan.components.eyebrow>{{ __('zunscan.about.who_eyebrow') }}</x-zunscan.components.eyebrow>
            <h2 class="mt-3 text-title text-balance text-zunscan-dark-gray">{{ __('zunscan.about.who_title') }}</h2>
            <p class="mt-4 max-w-3xl text-lead font-light text-zunscan-light-gray">{{ __('zunscan.about.who_lead') }}</p>

            <div class="mt-10 grid gap-6 sm:grid-cols-2">
                @foreach($partners as $partner)
                    <x-zunscan.components.card>
                        <p class="text-heading text-zunscan-dark-gray">{{ $partner['company'] }}</p>
                        <p class="mt-2 font-light text-zunscan-light-gray">{{ __('zunscan.about.partners.'.$partner['key']) }}</p>

                        <a href="{{ $partner['website'] }}" target="_blank" rel="noopener"
                           class="mt-4 inline-flex min-h-control items-center gap-2 font-bold text-zunscan-blue hover:underline">
                            {{ $partner['website_label'] }}

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                            </svg>
                        </a>
                    </x-zunscan.components.card>
                @endforeach
            </div>
        </div>
    </x-zunscan.components.section>

    <x-zunscan.patials.contactcta/>
</x-zunscan.layout>
