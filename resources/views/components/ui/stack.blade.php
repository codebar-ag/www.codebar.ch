@props(['gap' => '6', 'classAttributes' => ''])

@php
    $gapClasses = match ((string) $gap) {
        '2' => 'gap-2',
        '3' => 'gap-3',
        '4' => 'gap-4',
        '8' => 'gap-8',
        default => 'gap-6',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col {$gapClasses} {$classAttributes}"]) }}>
    {{ $slot }}
</div>
