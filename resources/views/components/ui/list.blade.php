@props([
    'classAttributes' => '',
    'variant' => 'stacked',
])

@php
    $variantClasses = match ($variant) {
        'grid' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12',
        default => 'divide-y divide-zinc-200',
    };
@endphp

<div {{ $attributes->merge(['class' => "{$variantClasses} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
