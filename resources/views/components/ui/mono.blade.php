@props(['classAttributes' => ''])

<code {{ $attributes->merge(['class' => "rounded bg-zinc-100 px-1.5 py-0.5 font-mono text-[0.85em] text-zinc-800 {$classAttributes}"]) }}>
    {{ $slot }}
</code>
