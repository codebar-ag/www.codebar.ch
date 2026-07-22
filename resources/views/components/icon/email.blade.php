@props(['label' => null])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"
     stroke-linecap="round" stroke-linejoin="round"
     @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
     {{ $attributes->merge(['stroke-width' => '2', 'class' => 'size-4']) }}>
    <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/>
    <path d="m21.854 2.147-10.94 10.939"/>
</svg>
