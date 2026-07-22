@props(['compact' => false])

@php
    $spacing = $compact ? 'gap-1 sm:gap-4 py-2 text-sm' : 'gap-2 sm:gap-6 py-4 px-2';
@endphp

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 border-t border-border-soft ' . $spacing]) }}>
    {{ $slot }}
</div>
