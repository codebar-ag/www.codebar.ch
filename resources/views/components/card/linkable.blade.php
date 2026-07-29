@props(['url', 'target' => '_self', 'surface' => 'plain'])

@php
    // Every clickable surface on the site — list rows, explore tiles, series
    // previous/next — shares this hover tint and focus ring. Add a surface here
    // rather than another hand-rolled hover somewhere in a view.
    $surfaces = [
        'plain' => 'rounded-pill py-4',
        'panel' => 'rounded-panel border border-border p-4',
        'panel-lg' => 'rounded-panel border border-border p-5',
    ];
@endphp

<a href="{{ $url }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   {{ $attributes->merge(['class' => 'group block cursor-pointer transition hover:bg-gray-50/50 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand '.($surfaces[$surface] ?? $surfaces['plain'])]) }}>
    {{ $slot }}
</a>
