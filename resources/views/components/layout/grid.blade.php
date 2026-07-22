@props(['cols' => 2, 'gap' => 'gap-4'])

@php
    // Standard responsive ladder: 1 column on mobile, 2 from sm, 3 from lg.
    $colClasses = match ((int) $cols) {
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        default => 'sm:grid-cols-2',
    };
@endphp

<div {{ $attributes->merge(['class' => trim('grid grid-cols-1 ' . $colClasses . ' ' . $gap)]) }}>
    {{ $slot }}
</div>
