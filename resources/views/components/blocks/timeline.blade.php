@props([
    'eyebrow' => null,
    'title',
    'teaser' => null,
    'milestones',
])

<x-ui.section>
    <x-ui.section-header
        :eyebrow="$eyebrow"
        :title="$title"
        :teaser="$teaser"
    />

    <ol class="mt-16 border-l border-zinc-200">
        @foreach($milestones as $milestone)
            <li class="relative pl-8 pb-12 last:pb-0">
                <span
                    aria-hidden="true"
                    class="absolute -left-[5px] top-2 size-2.5 rounded-full border border-zinc-300 bg-white"
                ></span>
                <p class="text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">
                    {{ $milestone->year }}
                </p>
                <h3 class="mt-2 text-xl font-semibold tracking-[-0.015em] text-zinc-950 text-balance md:text-2xl">
                    {{ __($milestone->title) }}
                </h3>
                @if(filled($milestone->body))
                    <p class="mt-3 max-w-3xl text-base leading-relaxed text-zinc-600">
                        {{ __($milestone->body) }}
                    </p>
                @endif
            </li>
        @endforeach
    </ol>
</x-ui.section>
