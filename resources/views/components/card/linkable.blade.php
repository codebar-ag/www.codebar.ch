@props(['url', 'target' => '_self', 'surface' => 'plain'])

@php
    $surfaces = [
        'plain' => 'rounded-pill py-4',
        'panel' => 'rounded-panel border border-border p-4',
        'panel-lg' => 'rounded-panel border border-border p-5',
    ];
@endphp

<a href="{{ $url }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   {{ $attributes->merge(['class' => 'group block cursor-pointer transition hover:bg-gray-50/50 focus-ring '.($surfaces[$surface] ?? $surfaces['plain'])]) }}>
    {{ $slot }}
</a>
