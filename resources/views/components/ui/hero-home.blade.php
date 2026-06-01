@props([
    'title' => null,
    'teaser' => null,
    'eyebrow' => null,
    'classAttributes' => '',
])

<section {{ $attributes->merge(['class' => "relative overflow-hidden {$classAttributes}"]) }}>
    {{-- Grid scanlines (squares) — soft, brand-tinted, masked top/bottom --}}
    <div aria-hidden="true" class="hero-home-grid pointer-events-none absolute inset-0"></div>

    <div class="relative mx-auto max-w-6xl px-6 py-24 md:py-36 lg:px-8 lg:py-44">
        @if(filled($eyebrow))
            <p class="text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">{{ $eyebrow }}</p>
        @endif

        @if(filled($title))
            <h1 class="hero-home-title @if(filled($eyebrow)) mt-6 @endif relative max-w-5xl text-balance font-semibold leading-[0.95] tracking-[-0.035em] text-zinc-950 text-5xl md:text-7xl lg:text-[5.5rem] lg:leading-[0.92]">
                <span class="relative z-10">{{ $title }}</span>
                <span aria-hidden="true" class="hero-home-band hero-home-band--1">{{ $title }}</span>
                <span aria-hidden="true" class="hero-home-band hero-home-band--2">{{ $title }}</span>
                <span aria-hidden="true" class="hero-home-band hero-home-band--3">{{ $title }}</span>
            </h1>
        @endif

        @if(filled($teaser))
            <p class="mt-8 max-w-2xl text-pretty text-lg leading-relaxed text-zinc-600 md:text-xl md:leading-relaxed">
                {{ $teaser }}
            </p>
        @endif

        @if(trim($slot))
            <div class="mt-10 flex flex-wrap items-center gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>

<style>
    .hero-home-grid {
        background-image:
            linear-gradient(to right,  rgba(80,4,114,.06) 0 1px, transparent 1px),
            linear-gradient(to bottom, rgba(80,4,114,.06) 0 1px, transparent 1px);
        background-size: 28px 28px;
        mix-blend-mode: multiply;
        mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
    }
    .hero-home-title { isolation: isolate; }
    .hero-home-band {
        position: absolute; inset: 0; pointer-events: none;
        font: inherit; letter-spacing: inherit; line-height: inherit; max-width: inherit;
        opacity: .42; mix-blend-mode: multiply;
    }
    .hero-home-band--1 { color: #c026d3; clip-path: inset(0 0 67% 0);   transform: translateX(-3px); }
    .hero-home-band--2 { color: #500472; clip-path: inset(34% 0 34% 0); transform: translateX( 4px); }
    .hero-home-band--3 { color: #2563eb; clip-path: inset(67% 0 0 0);   transform: translateX(-5px); }
</style>
