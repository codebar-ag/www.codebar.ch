@props([
    'href' => null,
    'label' => null,
    'title' => null,
    'target' => '_self',
    'variant' => 'default',
    'size' => 'sm',
])

@php
    $variants = [
        'default' => 'bg-gray-400/10 text-muted ring-1 ring-gray-400/20 ring-inset',
        'outline' => 'text-muted ring-1 ring-border ring-inset',
        'brand' => 'bg-brand text-white',
        'success' => 'bg-emerald-500/10 text-emerald-700 ring-1 ring-emerald-600/20 ring-inset',
        'metal' => 'bg-linear-to-b from-gray-100 via-white to-gray-300 text-gray-700 ring-1 ring-gray-400/40 ring-inset',
    ];

    $hovers = [
        'default' => 'hover:bg-gray-400/20 hover:text-gray-800',
        'outline' => 'hover:text-brand hover:ring-brand',
        'brand' => 'hover:bg-brand-strong',
        'success' => '',
        'metal' => '',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-sm',
        'md' => 'px-3.5 py-1.5 text-sm',
    ];

    $classes = trim(implode(' ', array_filter([
        'inline-flex items-center justify-center rounded-pill font-medium',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['sm'],
        $href ? 'tap-target transition cursor-pointer focus-ring' : null,
        $href ? ($hovers[$variant] ?? '') : null,
    ])));
@endphp

@if($href)
    <a href="{{ $href }}"
       @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
       @if(filled($title)) title="{{ $title }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
        {{ $slot }}
    </a>
@else
    <span @if(filled($title)) title="{{ $title }}" @endif
          {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
        {{ $slot }}
    </span>
@endif
