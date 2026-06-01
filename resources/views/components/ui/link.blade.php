@props([
    'href',
    'label' => null,
    'target' => '_self',
    'title' => null,
    'classAttributes' => '',
])

<a
    href="{{ $href }}"
    target="{{ $target }}"
    @if($target === '_blank') rel="noopener" @endif
    @if(filled($title) || filled($label)) title="{{ $title ?? $label }}" @endif
    {{ $attributes->merge(['class' => "text-zinc-950 underline decoration-zinc-300 underline-offset-4 transition-colors hover:decoration-zinc-950 {$classAttributes}"]) }}
>
    @if(filled($label)){{ $label }}@endif
    {{ $slot }}
</a>
