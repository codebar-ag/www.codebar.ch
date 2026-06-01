@props(['classAttributes' => ''])

<p {{ $attributes->merge(['class' => "max-w-2xl text-pretty text-base leading-relaxed text-zinc-700 {$classAttributes}"]) }}>
    {{ $slot }}
</p>
