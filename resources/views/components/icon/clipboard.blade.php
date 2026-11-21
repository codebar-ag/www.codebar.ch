@props(['label' => null])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"
     stroke-linecap="round" stroke-linejoin="round"
     @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
     {{ $attributes->merge(['stroke-width' => '2', 'class' => 'size-4']) }}>
    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
</svg>
