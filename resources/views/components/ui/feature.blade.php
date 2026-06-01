@props([
    'eyebrow' => null,
    'title',
    'teaser' => null,
    'classAttributes' => '',
])

<div {{ $attributes->merge(['class' => "border-t border-zinc-200 pt-8 {$classAttributes}"]) }}>
    @if(filled($eyebrow))
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ $eyebrow }}</p>
    @endif
    <h3 class="@if(filled($eyebrow)) mt-3 @endif text-xl font-semibold tracking-[-0.015em] text-zinc-950 text-balance md:text-2xl">
        {{ $title }}
    </h3>
    @if(filled($teaser))
        <p class="mt-3 text-base leading-relaxed text-zinc-600">{{ $teaser }}</p>
    @endif
    @if(trim($slot))
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
