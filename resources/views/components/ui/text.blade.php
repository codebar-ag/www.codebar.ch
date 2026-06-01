@props([
    'classAttributes' => '',
    'size' => 'base',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'text-sm text-zinc-500',
        'lg' => 'text-lg text-zinc-600',
        default => 'text-base text-zinc-700',
    };
@endphp

<p {{ $attributes->merge(['class' => "leading-relaxed {$sizeClasses} {$classAttributes}"]) }}>
    {{ $slot }}
</p>
