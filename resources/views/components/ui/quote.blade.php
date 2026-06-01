@props([
    'attribution' => null,
    'role' => null,
    'classAttributes' => '',
])

<figure {{ $attributes->merge(['class' => "mx-auto max-w-3xl {$classAttributes}"]) }}>
    <blockquote class="text-balance text-2xl font-medium leading-snug tracking-[-0.015em] text-zinc-950 md:text-3xl">
        <span class="select-none text-zinc-300" aria-hidden="true">“</span>{{ $slot }}<span class="select-none text-zinc-300" aria-hidden="true">”</span>
    </blockquote>
    @if(filled($attribution) || filled($role))
        <figcaption class="mt-8 flex items-center gap-3 text-sm">
            @if(filled($attribution))
                <span class="font-medium text-zinc-950">{{ $attribution }}</span>
            @endif
            @if(filled($role))
                <span class="text-zinc-500">{{ $role }}</span>
            @endif
        </figcaption>
    @endif
</figure>
