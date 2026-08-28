@props([
    'name',
    'value' => null,
    'rows' => 6,
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

<textarea id="{{ $id }}"
          name="{{ $name }}"
          rows="{{ $rows }}"
          @if(filled($placeholder)) placeholder="{{ $placeholder }}" @endif
          @if($hasError) aria-invalid="true" @endif
          @if(filled($describedBy)) aria-describedby="{{ $describedBy }}" @endif
          {{ $attributes->merge(['class' => 'block w-full rounded-panel border border-border-strong bg-white px-4 py-3 text-base text-gray-800 placeholder-hint transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-600 aria-invalid:focus:border-red-600 aria-invalid:focus:ring-red-600']) }}>{{ old($name, $value) }}</textarea>
