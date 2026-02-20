@props(['href', 'label', 'active' => false, 'target' => '_self'])

<a href="{{ $href }}"
   target="{{ $target }}"
   title="{{ $label }}"
   class="text-xl md:text-2xl transition {{ $active ? 'font-semibold text-black' : 'hover:text-black hover:font-semibold' }}">
    {{ $label }}
</a>
