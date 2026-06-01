@props([
    'company',
    'uid',
    'registryUrl',
])

<x-ui.hero
    :title="__('Imprint')"
    :teaser="__('Company registration and legal publisher information.')"
    eyebrow="{{ __('Legal') }}"
/>

<x-ui.section>
    <div class="mx-auto max-w-3xl">
        <div class="border-t border-zinc-200 pt-8">
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Company') }}</p>
            <address class="not-italic mt-4 text-zinc-600 leading-relaxed">
                <p class="font-semibold text-zinc-950">{{ $company }}</p>
                <p>Mühlematten 12</p>
                <p>CH-4455 Zunzgen</p>
                <p>{{ $uid }}</p>
            </address>
            <div class="mt-6">
                <x-ui.button :href="$registryUrl" :label="__('View on Zefix')" variant="secondary" target="_blank" />
            </div>
        </div>
    </div>
</x-ui.section>
