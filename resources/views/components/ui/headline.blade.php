@props([
    'title',
    'level' => 'h2',
    'classAttributes' => '',
])

@php
    $tag = in_array($level, ['h1', 'h2', 'h3'], true) ? $level : 'h2';
    $sizeClasses = match ($tag) {
        'h1' => 'text-4xl md:text-6xl lg:text-7xl font-semibold tracking-tight leading-[1.03]',
        'h3' => 'text-xl md:text-2xl font-semibold tracking-tight leading-tight',
        default => 'text-2xl md:text-4xl font-semibold tracking-tight leading-tight',
    };
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "{$sizeClasses} text-balance {$classAttributes}"]) }}>
    {{ $title }}
</{{ $tag }}>
