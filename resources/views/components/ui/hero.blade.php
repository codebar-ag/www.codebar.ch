@props([
    'title',
    'teaser' => null,
    'eyebrow' => null,
    'tone' => 'default',
    'classAttributes' => '',
])

@php
    $toneClasses = match ($tone) {
        'soft' => 'bg-[var(--surface-muted)]',
        'dark' => 'text-white bg-[color:color-mix(in_srgb,var(--brand-color)_24%,#0f172a)]',
        default => 'hero-gradient',
    };
@endphp

<section {{ $attributes->merge(['class' => "ui-region surface-card px-6 py-8 md:px-10 md:py-12 {$toneClasses} {$classAttributes}"]) }}>
    <x-ui.stack gap="4" class-attributes="reveal-on-scroll">
        @if(filled($eyebrow))
            <x-ui.eyebrow :text="$eyebrow" class-attributes="editorial-kicker" />
        @endif
        <x-ui.headline :title="$title" level="h1" class-attributes="max-w-4xl" />
        @if(filled($teaser))
            <x-ui.text size="lg" class-attributes="max-w-3xl text-[var(--text-muted)]">
                {{ $teaser }}
            </x-ui.text>
        @endif
        @if(trim($slot))
            <div class="flex flex-wrap gap-3 pt-2">
                {{ $slot }}
            </div>
        @endif
    </x-ui.stack>
</section>
