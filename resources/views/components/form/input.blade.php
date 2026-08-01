@props([
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'id' => null,
    'describedBy' => null,
])

@aware(['help' => null])

@php
    $id ??= $name;
    $hasError = $errors->has($name);

    $describedBy = collect([
        $describedBy,
        filled($help) ? $id.'-help' : null,
        $hasError ? $id.'-error' : null,
    ])->filter()->unique()->implode(' ');
@endphp

<input type="{{ $type }}"
       id="{{ $id }}"
       name="{{ $name }}"
       value="{{ old($name, $value) }}"
       @if(filled($placeholder)) placeholder="{{ $placeholder }}" @endif
       @if($hasError) aria-invalid="true" @endif
       @if(filled($describedBy)) aria-describedby="{{ $describedBy }}" @endif
       {{ $attributes->merge(['class' => 'block h-control w-full rounded-pill border border-border-strong bg-white px-4 text-base text-gray-800 placeholder-hint transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-600 aria-invalid:focus:border-red-600 aria-invalid:focus:ring-red-600']) }}>
