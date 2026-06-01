@props([
    'label' => null,
    'title' => null,
    'variant' => 'default',
    'classAttributes' => '',
])

@php
    $variantClasses = match ($variant) {
        'outline' => 'border border-zinc-300 text-zinc-700',
        'solid' => 'bg-zinc-950 text-white',
        default => 'border border-zinc-200 bg-zinc-50 text-zinc-600',
    };
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {$variantClasses} {$classAttributes}"]) }}
    @if(filled($title) || filled($label)) title="{{ $title ?? $label }}" @endif
>
    @if(filled($label)){{ $label }}@endif
    {{ $slot }}
</span>
