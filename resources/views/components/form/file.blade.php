@props(['name', 'accept' => null, 'id' => null])

@php
    $id ??= $name;
    $hasError = $errors->has($name);
@endphp

{{-- The file-selector button is a button, so it carries the button's pill radius
     and brand fill, and the shared control height keeps it in line with a text
     input sitting above it. --}}
<input type="file"
       id="{{ $id }}"
       name="{{ $name }}"
       @if(filled($accept)) accept="{{ $accept }}" @endif
       @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
       {{ $attributes->merge(['class' => 'block h-control w-full text-sm text-gray-800 file:mr-3 file:h-control file:rounded-pill file:border-0 file:bg-brand file:px-4 file:text-sm file:font-medium file:text-white hover:file:bg-brand-strong focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand']) }}>
