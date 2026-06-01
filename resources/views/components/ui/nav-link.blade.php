@props([
    'href',
    'label' => null,
    'classAttributes' => '',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => "rounded-md px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:text-zinc-950 {$classAttributes}"]) }}
>
    @if(filled($label)){{ $label }}@endif
    {{ $slot }}
</a>
