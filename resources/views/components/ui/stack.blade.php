@props(['gap' => '6', 'classAttributes' => ''])

@php
    $gapClass = match ((string) $gap) {
        '2' => 'gap-2',
        '3' => 'gap-3',
        '4' => 'gap-4',
        '8' => 'gap-8',
        '12' => 'gap-12',
        default => 'gap-6',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col {$gapClass} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
