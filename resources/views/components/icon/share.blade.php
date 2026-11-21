@props(['label' => null])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"
     stroke-linecap="round" stroke-linejoin="round"
     @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
     {{ $attributes->merge(['stroke-width' => '2', 'class' => 'size-4']) }}>
    <circle cx="18" cy="5" r="3"/>
    <circle cx="6" cy="12" r="3"/>
    <circle cx="18" cy="19" r="3"/>
    <path d="m8.59 13.51 6.83 3.98"/>
    <path d="m15.41 6.51-6.82 3.98"/>
</svg>
