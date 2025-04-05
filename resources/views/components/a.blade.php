@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null])

<a target="{{ $target }}" href="{{ $href }}" title="{{ $title ?? $label }}"
   class="{{ $classAttributes }} hover:text-black hover:font-semibold transition">
    {{ $label }}
</a>