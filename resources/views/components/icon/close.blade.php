@props(['label' => null])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
     @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
     {{ $attributes->merge(['stroke-width' => '2', 'class' => 'size-4']) }}>
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
</svg>
