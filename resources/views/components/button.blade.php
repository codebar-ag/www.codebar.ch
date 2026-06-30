@props([
    'href' => null,
    'label' => null,
    'variant' => 'primary',
    'target' => '_self',
])

@php
    $base = 'inline-flex items-center justify-center px-5 py-2.5 rounded-md text-sm font-medium transition w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-(--brand)';

    $variants = [
        'primary' => 'text-white bg-(--brand) hover:bg-brand-strong',
        'outline' => 'bg-white border border-(--brand) text-(--brand) hover:bg-(--brand) hover:text-white',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<a href="{{ $href }}" target="{{ $target }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</a>
