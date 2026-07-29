@props(['variant' => 'info'])

@php
    $variants = [
        'info' => 'bg-gray-400/10 text-gray-800 ring-gray-400/20',
        'success' => 'bg-green-50 text-green-900 ring-green-600/20',
        'error' => 'bg-red-50 text-red-900 ring-red-600/20',
    ];
@endphp

<div role="{{ $variant === 'error' ? 'alert' : 'status' }}"
     {{ $attributes->merge(['class' => 'rounded-panel px-4 py-3 ring-1 ring-inset '.($variants[$variant] ?? $variants['info'])]) }}>
    {{ $slot }}
</div>
