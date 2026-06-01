@props([
    'title',
    'teaser' => null,
    'classAttributes' => '',
])

<article {{ $attributes->merge(['class' => "border-t border-zinc-200 pt-8 {$classAttributes}"]) }}>
    <h3 class="text-xl md:text-2xl font-semibold tracking-tight text-zinc-950 text-balance">{{ $title }}</h3>
    @if(filled($teaser))
        <p class="mt-3 text-base leading-relaxed text-zinc-600">{{ $teaser }}</p>
    @endif
    @if(trim($slot))
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</article>
