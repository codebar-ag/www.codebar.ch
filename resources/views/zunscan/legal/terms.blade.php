@php
    $company = config('zunscan.company');
@endphp

<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.legal.imprint_title')" :subtitle="__('zunscan.legal.imprint_subtitle')"/>

    <x-zunscan.components.section>
        {{-- Narrower than the section: legal text is long-form prose and a 60rem
             measure is roughly twice a comfortable reading width. --}}
        <div class="mx-auto max-w-3xl space-y-6">
            {{-- Rendered from config rather than from the markdown. Single newlines
                 collapse to spaces in CommonMark, so an address written as markdown
                 came out as one run-on line, and it was a second place the address
                 had to be kept correct. --}}
            <x-zunscan.components.card class="p-6 sm:p-10">
                <h2 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.legal.company_title') }}</h2>

                <address class="mt-4 not-italic font-light leading-7 text-zunscan-light-gray">
                    <span class="font-bold text-zunscan-dark-gray">{{ $company['legal_name'] }}</span><br>
                    {{ $company['street'] }}<br>
                    CH-{{ $company['postal_code'] }} {{ $company['locality'] }}
                </address>

                <dl class="mt-4 grid gap-x-6 gap-y-1 sm:grid-cols-[auto_1fr]">
                    <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.legal.uid') }}</dt>
                    <dd class="font-light text-zunscan-light-gray">{{ $company['uid'] }}</dd>

                    <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.legal.email') }}</dt>
                    <dd>
                        <a href="mailto:{{ $company['email'] }}"
                           class="text-zunscan-blue hover:underline">{{ $company['email'] }}</a>
                    </dd>
                </dl>
            </x-zunscan.components.card>

            <x-zunscan.components.card class="p-6 sm:p-10">
                <div class="legal-prose">
                    {!! $body !!}
                </div>
            </x-zunscan.components.card>
        </div>
    </x-zunscan.components.section>
</x-zunscan.layout>
