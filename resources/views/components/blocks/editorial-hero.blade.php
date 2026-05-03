@props([
    'title',
    'teaser' => null,
    'eyebrow' => null,
    'tone' => 'default',
    'classAttributes' => '',
])

@php
    $toneClasses = match ($tone) {
        'dark' => 'bg-[color:color-mix(in_srgb,var(--brand-color)_26%,#0f172a)] text-white',
        'muted' => 'bg-[var(--surface-muted)]',
        default => 'hero-gradient',
    };
@endphp

<x-ui.hero
    :title="$title"
    :teaser="$teaser"
    :eyebrow="$eyebrow"
    class-attributes="editorial-stripe {{ $toneClasses }} {{ $classAttributes }}"
/>
