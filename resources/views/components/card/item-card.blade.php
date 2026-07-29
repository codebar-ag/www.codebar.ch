@props(['url' => null, 'title', 'teaser' => null, 'tags' => [], 'publishedAt' => null, 'target' => '_self', 'level' => 3])

{{-- The list row behind services, products, technologies, open source and the
     latest-news block. Given no url it renders the same row without the link and
     without the «Learn more» affordance, so an index whose entries have no detail
     page still reads as the same list. --}}
@if(filled($url))
    <x-card.linkable :url="$url" :target="$target" {{ $attributes }}>
        <x-card.item-card-body :title="$title" :teaser="$teaser" :tags="$tags" :published-at="$publishedAt" :level="$level"/>
    </x-card.linkable>
@else
    <div {{ $attributes->merge(['class' => 'py-4']) }}>
        <x-card.item-card-body :title="$title" :teaser="$teaser" :tags="$tags" :published-at="$publishedAt" :level="$level" :linked="false"/>
    </div>
@endif
