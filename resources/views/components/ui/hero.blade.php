@props([
    'title' => null,
    'teaser' => null,
    'eyebrow' => null,
    'classAttributes' => '',
])

<section {{ $attributes->merge(['class' => "relative border-b border-zinc-200 bg-zinc-50 {$classAttributes}"]) }}>
    <div class="mx-auto max-w-6xl px-6 py-8 md:py-12 lg:px-8 lg:py-14">
        @if(filled($eyebrow))
            <p class="text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">{{ $eyebrow }}</p>
        @endif
        @if(filled($title))
            <h1 class="@if(filled($eyebrow)) mt-3 @endif max-w-5xl text-balance font-semibold leading-[0.95] tracking-[-0.035em] text-zinc-950 text-4xl md:text-5xl lg:text-6xl lg:leading-[0.95]">
                {{ $title }}
            </h1>
        @endif
        @if(filled($teaser))
            <p class="mt-4 max-w-2xl text-pretty text-base leading-relaxed text-zinc-600 md:text-lg md:leading-relaxed">
                {{ $teaser }}
            </p>
        @endif
        @if(trim($slot))
            <div class="mt-6 flex flex-wrap items-center gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>
