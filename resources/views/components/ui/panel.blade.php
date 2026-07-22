@props(['id' => null, 'variant' => 'surface'])

@php
    $bg = $variant === 'plain' ? 'bg-white' : 'bg-surface';
@endphp

<div @if($id) id="{{ $id }}" @endif
     {{ $attributes->merge(['class' => 'border border-border rounded-panel ' . $bg]) }}>
    {{ $slot }}
</div>
