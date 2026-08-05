@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
])

{{--
    The header band is its own surface, not a rule drawn across the same one.
    Hero and content used to share the paper texture, so no divider could carry
    enough weight to separate them. Now the page reads as three zones:
    dark-blue header → paper content → blue footer.

    Colour choices are contrast-driven, not decorative: the subtitle is white at
    80% rather than light-blue, because #0093b8 on #16395a measures about 3:1 and
    would fail AA for text this size.
--}}
<div class="bg-paper-dark">
    <div class="mx-auto max-w-5xl px-6 pb-16">
        <x-zunscan.patials.header/>

        <div class="pt-14 sm:pt-20">
            @if($eyebrow)
                <p class="mb-3 text-eyebrow uppercase text-white/70">{{ $eyebrow }}</p>
            @endif

            <h1 class="text-display text-balance text-white">{{ $title }}</h1>

            @if($subtitle)
                <p class="mt-4 max-w-2xl text-lead text-white/80">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>
