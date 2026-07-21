@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null, 'variant' => 'default'])

@php
    $variantClasses = match ($variant) {
        'brand' => 'bg-brand text-white hover:bg-brand-strong',
        default => 'bg-gray-400/10 text-gray-600 hover:bg-gray-400/20 hover:text-gray-800 hover:font-semibold ring-1 ring-gray-400/20 ring-inset',
    };
@endphp

<a target="{{ $target }}" href="{{ $href }}" title="{{ $title ?? $label }}"
   {{ $attributes->merge(['class' => $classAttributes.' '.$variantClasses.' inline-flex items-center rounded-md px-2 py-1 text-sm font-medium cursor-pointer']) }}>
    {{ $label }}
    {{ $slot }}
</a>
