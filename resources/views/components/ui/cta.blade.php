@props([
    'title',
    'teaser' => null,
    'classAttributes' => '',
])

<div {{ $attributes->merge(['class' => "mx-auto max-w-3xl text-center {$classAttributes}"]) }}>
    <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-950 text-balance">{{ $title }}</h2>
    @if(filled($teaser))
        <p class="mt-4 text-lg leading-relaxed text-zinc-600">{{ $teaser }}</p>
    @endif
    @if(trim($slot))
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
