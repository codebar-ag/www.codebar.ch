@props([
    'columns' => '2',
    'gap' => '8',
    'classAttributes' => '',
])

@php
    $colClasses = match ((string) $columns) {
        '1' => 'grid-cols-1',
        '2' => 'grid-cols-1 md:grid-cols-2',
        '3' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        '4' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-2',
    };
    $gapClass = match ((string) $gap) {
        '4' => 'gap-4',
        '6' => 'gap-6',
        '12' => 'gap-12',
        default => 'gap-8',
    };
@endphp

<div {{ $attributes->merge(['class' => "grid {$colClasses} {$gapClass} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
