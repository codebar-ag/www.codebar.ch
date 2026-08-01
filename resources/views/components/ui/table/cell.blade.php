@props(['as' => 'td', 'scope' => null, 'align' => 'start', 'hide' => false])

@php
    $classes = trim(implode(' ', array_filter([
        'py-2',
        $align === 'end' ? 'text-right' : 'text-left',
        $as === 'th' ? 'font-medium' : null,
        $hide ? 'hidden sm:table-cell' : null,
    ])));
@endphp

<{{ $as }} @if(filled($scope)) scope="{{ $scope }}" @endif {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</{{ $as }}>
