@props([
    'title' => null,
    'level' => 'h2',
    'classAttributes' => '',
])

@php
    $tag = in_array($level, ['h1', 'h2', 'h3', 'h4'], true) ? $level : 'h2';
    $sizeClasses = match ($tag) {
        'h1' => 'text-4xl md:text-6xl lg:text-7xl font-semibold leading-[0.96] tracking-[-0.03em]',
        'h3' => 'text-xl md:text-2xl font-semibold leading-snug tracking-[-0.015em]',
        'h4' => 'text-lg md:text-xl font-semibold tracking-tight',
        default => 'text-3xl md:text-4xl lg:text-5xl font-semibold leading-[1.04] tracking-[-0.025em]',
    };
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "{$sizeClasses} text-balance text-zinc-950 {$classAttributes}"]) }}>
    @if(filled($title)){{ $title }}@endif
    {{ $slot }}
</{{ $tag }}>
