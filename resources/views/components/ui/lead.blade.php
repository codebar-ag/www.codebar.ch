@props(['classAttributes' => ''])

<p {{ $attributes->merge(['class' => "max-w-3xl text-pretty text-lg leading-relaxed text-zinc-600 md:text-xl md:leading-relaxed {$classAttributes}"]) }}>
    {{ $slot }}
</p>
