@props(['href', 'label' => null, 'target' => '_self', 'title' => null, 'variant' => 'default'])

@php
    $variantClasses = match ($variant) {
        'brand' => 'bg-brand text-white hover:bg-brand-strong',
        default => 'bg-gray-400/10 text-muted hover:bg-gray-400/20 hover:text-gray-800 ring-1 ring-gray-400/20 ring-inset',
    };
@endphp

<a href="{{ $href }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   @if(filled($title)) title="{{ $title }}" @endif
   {{ $attributes->merge(['class' => $variantClasses . ' inline-flex items-center rounded-pill px-3 py-2 sm:px-2 sm:py-1 text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-brand']) }}>
    {{ $label }}
    {{ $slot }}
</a>
