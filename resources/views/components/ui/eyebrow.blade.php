@props(['text', 'classAttributes' => ''])

<p {{ $attributes->merge(['class' => "text-xs font-medium uppercase tracking-[0.2em] text-zinc-500 {$classAttributes}"]) }}>
    {{ $text }}
</p>
