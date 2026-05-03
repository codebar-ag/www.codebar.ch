@props([
    'label',
    'title' => null,
    'variant' => 'soft',
    'classAttributes' => '',
])

@php
    $variantClasses = match ($variant) {
        'outline' => 'bg-transparent border border-gray-300 text-gray-700',
        default => 'brand-pill',
    };
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {$variantClasses} {$classAttributes}"]) }}
    title="{{ $title ?? $label }}"
>
    {{ $label }}
    {{ $slot }}
</span>
