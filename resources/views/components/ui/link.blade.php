@props([
    'href',
    'label',
    'target' => '_self',
    'title' => null,
    'classAttributes' => '',
])

<a
    {{ $attributes->merge(['class' => "brand-link underline-offset-2 hover:underline decoration-1 {$classAttributes}"]) }}
    href="{{ $href }}"
    target="{{ $target }}"
    title="{{ $title ?? $label }}"
>
    {{ $label }}
    {{ $slot }}
</a>
