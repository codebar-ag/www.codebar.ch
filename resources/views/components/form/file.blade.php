@props(['name', 'accept' => null, 'id' => null, 'describedBy' => null])

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

<input type="file"
       id="{{ $id }}"
       name="{{ $name }}"
       @if(filled($accept)) accept="{{ $accept }}" @endif
       @if($hasError) aria-invalid="true" @endif
       @if(filled($describedBy)) aria-describedby="{{ $describedBy }}" @endif
       {{ $attributes->merge(['class' => 'block h-control w-full text-sm text-gray-800 file:mr-3 file:h-control file:rounded-pill file:border-0 file:bg-brand file:px-4 file:text-sm file:font-medium file:text-white hover:file:bg-brand-strong focus-ring']) }}>
