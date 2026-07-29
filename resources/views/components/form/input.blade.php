@props([
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'id' => null,
    'describedBy' => null,
])

@php
    $id ??= $name;
    $hasError = $errors->has($name);

    // The one control appearance in the app. Every text control — including the
    // combobox — renders through this component, so heights, padding, radius and
    // focus ring can never drift apart. border-strong and hint are the lightest
    // grays that still clear WCAG 1.4.11 (3:1) and 1.4.3 (4.5:1) on white.
    $describedBy = collect([
        $describedBy,
        $hasError ? $id.'-error' : null,
    ])->filter()->implode(' ');
@endphp

<input type="{{ $type }}"
       id="{{ $id }}"
       name="{{ $name }}"
       value="{{ old($name, $value) }}"
       @if(filled($placeholder)) placeholder="{{ $placeholder }}" @endif
       @if($hasError) aria-invalid="true" @endif
       @if(filled($describedBy)) aria-describedby="{{ $describedBy }}" @endif
       {{ $attributes->merge(['class' => 'block h-control w-full rounded-pill border border-border-strong bg-white px-4 text-base text-gray-800 placeholder-hint transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-600 aria-invalid:focus:border-red-600 aria-invalid:focus:ring-red-600']) }}>
