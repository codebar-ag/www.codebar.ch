@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null])

<a target="{{ $target }}" href="{{ $href }}" title="{{ $title ?? $label }}"
   class="{{ $classAttributes }} inline-flex items-center rounded-md bg-gray-400/10  px-2 py-1 text-sm font-medium text-gray-400 hover:bg-gray-400/20 hover:text-black hover:font-semibold ring-1 ring-gray-400/20 ring-inset cursor-pointer">
    {{ $label }}
    {{ $slot }}
</a>
