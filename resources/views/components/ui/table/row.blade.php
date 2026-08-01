@props(['variant' => 'body'])

@php
    $variants = [
        'head' => 'border-b border-border text-muted',
        'body' => 'border-b border-border-soft text-gray-800',
        'foot' => 'font-semibold text-gray-800',
    ];
@endphp

<tr {{ $attributes->merge(['class' => $variants[$variant] ?? $variants['body']]) }}>{{ $slot }}</tr>
