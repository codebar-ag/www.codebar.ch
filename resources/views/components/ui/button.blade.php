@props([
    'href' => null,
    'label',
    'variant' => 'primary',
    'target' => '_self',
    'classAttributes' => '',
])

@php
    $classes = match ($variant) {
        'ghost' => 'border border-transparent text-[var(--text-muted)] hover:bg-[var(--surface-muted)]',
        'outline' => 'border text-[var(--brand-color-strong)] hover:bg-[var(--brand-color-soft)]',
        default => 'text-white shadow-sm hover:opacity-95',
    };
@endphp

@if(filled($href))
    <a
        {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold tracking-[0.01em] transition {$classes} {$classAttributes}"]) }}
        @if($variant === 'primary') style="background-color: var(--brand-color);" @endif
        @if($variant === 'outline') style="border-color: color-mix(in srgb, var(--brand-color) 50%, transparent);" @endif
        href="{{ $href }}"
        target="{{ $target }}"
    >
        {{ $label }}
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold tracking-[0.01em] transition {$classes} {$classAttributes}"]) }}
        @if($variant === 'primary') style="background-color: var(--brand-color);" @endif
        @if($variant === 'outline') style="border-color: color-mix(in srgb, var(--brand-color) 50%, transparent);" @endif
        type="button"
    >
        {{ $label }}
        {{ $slot }}
    </button>
@endif
