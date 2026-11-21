@props(['label' => null])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"
     stroke-linecap="round" stroke-linejoin="round"
     @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
     {{ $attributes->merge(['stroke-width' => '2', 'class' => 'size-4']) }}>
    <path d="M20 6 9 17l-5-5"/>
</svg>
