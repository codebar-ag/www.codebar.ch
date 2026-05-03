@props(['text', 'classAttributes' => ''])

<p {{ $attributes->merge(['class' => "text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 {$classAttributes}"]) }}>
    {{ $text }}
</p>
