@props(['classAttributes' => ''])

<article {{ $attributes->merge(['class' => "border-t border-zinc-200 pt-8 {$classAttributes}"]) }}>
    {{ $slot }}
</article>
