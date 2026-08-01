@props([
    'topics' => [],
    'publishedAt' => null,
    'readingMinutes' => null,
    'authorName' => null,
    'authorImage' => null,
])

@php
    $topics = collect($topics)->filter()->unique()->values();
@endphp

{{-- The tail every news card ends with — topics, byline, affordance. A row and the lead
     banner differ above this point and agree below it, so it lives in one file. --}}
<div {{ $attributes }}>
    {{-- The topics get their own row rather than riding along in the caption: an article
         can carry several, and a wrapping chip list pushes the author and date around
         inside their own line. They stay below the title, though — above it they read as
         a second heading. --}}
    @if($topics->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($topics as $topic)
                <x-ui.badge :label="$topic" size="sm"/>
            @endforeach
        </div>
    @endif

    {{-- Every piece of metadata on one line. Splitting author and date above the title
         from the reading time below it read as two unrelated captions. --}}
    <div class="{{ $topics->isNotEmpty() ? 'mt-3' : 'mt-4' }} flex flex-wrap items-center gap-x-2 gap-y-2 text-sm text-muted">
        @if($authorName)
            <x-news.avatar :image="$authorImage" :px="96" radius="rounded" class="me-1 size-6"/>
            <span class="text-gray-800">{{ $authorName }}</span>
        @endif

        @if($publishedAt)
            @if($authorName)<span aria-hidden="true">·</span>@endif
            <time datetime="{{ $publishedAt->toDateString() }}">{{ $publishedAt->translatedFormat('j. F Y') }}</time>
        @endif

        @if($readingMinutes)
            @if($authorName || $publishedAt)<span aria-hidden="true">·</span>@endif
            <span>{{ __(':count min read', ['count' => $readingMinutes]) }}</span>
        @endif
    </div>

    {{-- A span, not a link: the whole card already is one, and an anchor inside an anchor
         is invalid. It is the same affordance every list row on the site carries — see
         x-card.item-card-body, which words and animates it alike. --}}
    <span class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-brand">
        {{ __('Learn more') }}
        <x-icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1"/>
    </span>
</div>
