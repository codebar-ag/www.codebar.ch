@props([
    'columns' => '2',
    'classAttributes' => '',
])

@php
    $gridCols = match ($columns) {
        '3' => 'panel-grid',
        default => 'grid grid-cols-1 lg:grid-cols-2 gap-6',
    };
@endphp

<div {{ $attributes->merge(['class' => "ui-region {$gridCols} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
