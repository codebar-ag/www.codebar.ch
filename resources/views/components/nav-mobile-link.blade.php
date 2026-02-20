@props(['href', 'label', 'active' => false, 'target' => '_self'])

<a @click.stop
   href="{{ $href }}"
   target="{{ $target }}"
   title="{{ $label }}"
   class="block py-3 text-center text-xl transition rounded {{ $active ? 'font-semibold text-black bg-gray-100' : 'bg-gray-50/50 hover:text-black hover:font-semibold' }}">
    {{ $label }}
</a>
