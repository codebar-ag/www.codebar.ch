@php
    $t = 'components.odoo_pricing.';
    $iconButton = 'inline-flex size-12 shrink-0 cursor-pointer items-center justify-center rounded-full border border-brand bg-white text-brand transition hover:bg-brand hover:text-white focus-ring';
@endphp

<x-report-layout :page="$page">
    <x-slot:actions>
        <a href="{{ $calculatorUrl }}" title="{{ __($t.'report.adjust') }}" aria-label="{{ __($t.'report.adjust') }}"
           class="{{ $iconButton }}" data-adjust>
            <x-icon.sliders class="size-5"/>
        </a>
    </x-slot:actions>

    <x-ui.badge :label="__($t.'badge')"/>
    <h1 class="mt-4 mb-4 text-display font-bold text-balance text-gray-900">{{ __($t.'report.title') }}</h1>
    <p class="max-w-3xl text-lead font-light text-gray-800">
        {{ __($t.'report.intro', ['users' => $headline, 'billing' => __($t.'report.billing_'.$selection->billing)]) }}
    </p>

    <x-layout.section>
        <div class="divide-y divide-border overflow-hidden rounded-panel border border-border-strong bg-white">
            @include('app.erp.odoo._costs', ['heading' => 'h2'])
        </div>
    </x-layout.section>

    @include('app.erp.odoo._details')

    <x-band.cta-band :title="__($t.'report.cta_title')" :body="__($t.'report.cta_body')">
        <x-ui.button :href="localized_route('contact.index')" size="lg">
            {{ __($t.'cta.button') }}
            <x-icon.arrow-right class="size-4"/>
        </x-ui.button>
    </x-band.cta-band>
</x-report-layout>
