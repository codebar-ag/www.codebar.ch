@props([
    'href' => null,
    'label' => null,
    'variant' => 'primary',
    'target' => '_self',
    'type' => 'submit',
])

@php
    $base = 'inline-flex items-center justify-center px-5 py-2.5 rounded-pill text-sm font-medium transition w-full sm:w-auto focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand';

    $variants = [
        'primary' => 'text-white bg-brand hover:bg-brand-strong',
        'outline' => 'bg-white border border-brand text-brand hover:bg-brand hover:text-white',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
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
