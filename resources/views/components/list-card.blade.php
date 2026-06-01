@props([
    'url',
    'title',
    'teaser' => null,
    'tags' => [],
    'target' => '_self',
])

@php
    $tagCollection = collect($tags);
@endphp

<a
    href="{{ $url }}"
    target="{{ $target }}"
    @if($target === '_blank') rel="noopener" @endif
    class="group block py-8 transition-colors"
>
    <h3 class="text-xl md:text-2xl font-semibold tracking-tight text-zinc-950 transition-colors group-hover:text-brand">
        {{ $title }}
    </h3>
    @if(filled($teaser))
        <p class="mt-3 text-base leading-relaxed text-zinc-600">
            {{ $teaser }}
        </p>
    @endif
    @if($tagCollection->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($tagCollection as $tag)
                <span class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    @endif
    <span class="mt-5 inline-flex items-center gap-1 text-sm font-medium text-zinc-500 transition-colors group-hover:text-zinc-950">
        {{ __('Read more') }}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-0.5">
            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
        </svg>
    </span>
</a>
