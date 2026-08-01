@props([
    'href' => null,
    'label' => null,
    'variant' => 'primary',
    'size' => 'md',
    'target' => '_self',
    'type' => 'submit',
    'block' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-pill font-medium transition cursor-pointer '
          . 'focus-ring '
          . 'disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none';

    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-strong',
        'outline' => 'bg-white border border-brand text-brand hover:bg-brand hover:text-white',
        'ghost' => 'text-brand hover:bg-surface',
    ];

    $sizes = [
        'sm' => 'h-control-sm px-4 text-sm',
        'md' => 'h-control px-5 text-sm',
        'lg' => 'h-control px-7 text-base',
    ];

    $classes = trim(implode(' ', array_filter([
        $base,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $block ? 'w-full' : null,
    ])));
@endphp

@if($href)
    <a href="{{ $href }}"
       @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot->isEmpty() ? $label : $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot->isEmpty() ? $label : $slot }}
    </button>
@endif
