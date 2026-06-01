@props([
    'href',
    'label' => null,
    'target' => '_self',
    'classAttributes' => '',
])

<a
    href="{{ $href }}"
    target="{{ $target }}"
    @if($target === '_blank') rel="noopener" @endif
    {{ $attributes->merge(['class' => "block text-sm text-zinc-500 transition-colors hover:text-zinc-950 {$classAttributes}"]) }}
>
    @if(filled($label)){{ $label }}@endif
    {{ $slot }}
</a>
