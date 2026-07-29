@props(['title', 'teaser' => null, 'tags' => [], 'publishedAt' => null, 'level' => 3, 'linked' => true])

{{-- The contents of a list row. Split out of x-card.item-card so a row with a
     detail page and a row without one are the same markup inside two wrappers. --}}
<div class="flex flex-col gap-1">
    <h{{ $level }} class="text-subheading font-semibold text-gray-800 {{ $linked ? 'group-hover:text-brand' : '' }}">
        {{ $title }}
    </h{{ $level }}>

    @if(filled($teaser))
        <div class="text-muted">{{ $teaser }}</div>
    @endif

    {{-- Topic and date share one caption line, the same order the news card uses,
         so the latest-news block and the news index read as one thing. Rows without
         a date — services, products, technologies — keep the bare tag list. --}}
    @if(filled($tags) || $publishedAt)
        <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-2 text-sm text-muted">
            <x-data.tag-list :tags="$tags"/>

            @if($publishedAt)
                <time datetime="{{ $publishedAt->toDateString() }}">{{ $publishedAt->translatedFormat('j. F Y') }}</time>
            @endif
        </div>
    @endif

    @if($linked)
        <span class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-brand">
            {{ __('Learn more') }}
            <x-icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1"/>
        </span>
    @endif
</div>
