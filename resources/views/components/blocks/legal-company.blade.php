@props([
    'company',
    'uid',
    'registryUrl',
])

<x-blocks.editorial-hero
    :title="__('Imprint')"
    :teaser="__('Company registration and legal publisher information.')"
    eyebrow="{{ __('Legal') }}"
    tone="muted"
/>

<x-ui.section class-attributes="editorial-divider">
    <x-blocks.content-river>
        <x-ui.card>
            <x-ui.eyebrow text="{{ __('Company') }}" />
            <address class="not-italic mt-4 text-[var(--text-muted)] leading-relaxed">
                <p class="font-semibold text-[var(--text-strong)]">{{ $company }}</p>
                <p>Mühlematten 12</p>
                <p>CH-4455 Zunzgen</p>
                <p>{{ $uid }}</p>
            </address>
            <x-ui.button :href="$registryUrl" :label="__('Zefix')" variant="outline" target="_blank" class-attributes="mt-4" />
        </x-ui.card>
    </x-blocks.content-river>
</x-ui.section>
