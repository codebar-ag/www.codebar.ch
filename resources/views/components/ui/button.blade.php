@props([
    'href' => null,
    'label' => null,
    'variant' => 'primary',
    'size' => 'md',
    'target' => '_self',
    'classAttributes' => '',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'px-3.5 py-1.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $base = "inline-flex items-center justify-center gap-1.5 rounded-md font-medium transition-colors {$sizeClasses}";

    $variantClasses = match ($variant) {
        'secondary', 'outline' => 'border border-zinc-200 bg-white text-zinc-900 hover:border-zinc-300 hover:bg-zinc-50',
        'ghost' => 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-950',
        'brand' => 'bg-brand text-white hover:bg-brand-strong',
        default => 'bg-zinc-950 text-white hover:bg-zinc-800',
    };

    $classes = trim("{$base} {$variantClasses} {$classAttributes}");
@endphp

@if(filled($href))
    <a
        {{ $attributes->merge(['class' => $classes]) }}
        href="{{ $href }}"
        target="{{ $target }}"
        @if($target === '_blank') rel="noopener" @endif
    >
        @if(filled($label)){{ $label }}@endif
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if(filled($label)){{ $label }}@endif
        {{ $slot }}
    </button>
@endif
