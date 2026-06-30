@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null, 'download' => null])

<a target="{{ $target }}" href="{{ $href }}" title="{{ $title ?? $label }}"
   @if(filled($download)) download="{{ $download }}" @endif
   class="{{ $classAttributes }} hover:text-brand hover:font-semibold transition">
    {{ $label }}
</a>