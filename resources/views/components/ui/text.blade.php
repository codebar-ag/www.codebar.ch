@props([
    'classAttributes' => '',
    'size' => 'base',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'text-sm',
        'lg' => 'text-lg',
        default => 'text-base',
    };
@endphp

<p {{ $attributes->merge(['class' => "leading-relaxed text-gray-700 {$sizeClasses} {$classAttributes}"]) }}>
    {{ $slot }}
</p>
