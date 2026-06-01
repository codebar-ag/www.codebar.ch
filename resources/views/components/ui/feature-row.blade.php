@props([
    'columns' => '3',
    'classAttributes' => '',
])

@php
    $colClasses = match ((string) $columns) {
        '2' => 'md:grid-cols-2',
        '3' => 'md:grid-cols-3',
        '4' => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-3',
    };
@endphp

<div {{ $attributes->merge(['class' => "grid grid-cols-1 gap-x-12 gap-y-12 {$colClasses} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
