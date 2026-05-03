@props(['classAttributes' => ''])

<article {{ $attributes->merge(['class' => "ui-region surface-card surface-card-hover reveal-on-scroll p-5 md:p-6 {$classAttributes}"]) }}>
    {{ $slot }}
</article>
