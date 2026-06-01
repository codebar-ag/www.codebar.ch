@props([
    'eyebrow' => null,
    'title',
    'teaser' => null,
    'align' => 'left',
    'classAttributes' => '',
])

@php
    $alignClass = $align === 'center' ? 'mx-auto text-center' : '';
@endphp

<div {{ $attributes->merge(['class' => "max-w-3xl {$alignClass} {$classAttributes}"]) }}>
    @if(filled($eyebrow))
        <p class="text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">{{ $eyebrow }}</p>
    @endif
    <h2 class="@if(filled($eyebrow)) mt-4 @endif text-3xl font-semibold leading-[1.04] tracking-[-0.025em] text-zinc-950 text-balance md:text-4xl lg:text-5xl">
        {{ $title }}
    </h2>
    @if(filled($teaser))
        <p class="mt-5 text-pretty text-lg leading-relaxed text-zinc-600">{{ $teaser }}</p>
    @endif
</div>
