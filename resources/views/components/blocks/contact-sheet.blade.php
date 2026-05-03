@props([
    'company',
    'email',
    'phone' => '+41 61 515 60 90',
])

<x-ui.section class-attributes="editorial-divider">
    <div class="panel-grid ui-region">
        <x-ui.card class-attributes="editorial-stripe">
            <x-ui.eyebrow text="{{ __('Call') }}" />
            <x-ui.headline :title="$phone" level="h3" class-attributes="mt-3 text-2xl" />
            <x-ui.link :href="'tel:0041615156090'" :label="$phone" class-attributes="mt-4 inline-flex px-2 py-1" />
        </x-ui.card>

        <x-ui.card class-attributes="editorial-stripe">
            <x-ui.eyebrow text="{{ __('Email') }}" />
            <x-ui.headline :title="$email" level="h3" class-attributes="mt-3 text-2xl" />
            <x-ui.link :href="'mailto:'.$email.'?subject=Hello%20World!'" :label="$email" class-attributes="mt-4 inline-flex px-2 py-1" />
        </x-ui.card>

        <x-ui.card>
            <x-ui.eyebrow text="{{ __('Offices') }}" />
            <div class="mt-3 space-y-4 text-sm">
                <address class="not-italic text-[var(--text-muted)]">
                    <p class="font-semibold text-[var(--text-strong)]">{{ $company }}</p>
                    <p>{{ __('Headquarter') }}</p>
                    <p>Haupstrasse 91</p>
                    <p>CH-4455 Zunzgen</p>
                </address>
                <address class="not-italic text-[var(--text-muted)]">
                    <p class="font-semibold text-[var(--text-strong)]">{{ $company }}</p>
                    <p>{{ __('Branch office') }}</p>
                    <p>Langegasse 39</p>
                    <p>CH-4104 Oberwil</p>
                </address>
            </div>
        </x-ui.card>
    </div>
</x-ui.section>
