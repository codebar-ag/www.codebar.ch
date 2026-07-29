@props(['articles' => null])

@php
    $articles ??= collect();
    $urlFor = fn ($entry) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry]);
    // The same chip the news index puts on a card, so an article carries one topic
    // across the site instead of a series title here and a tag list there.
    $topicFor = fn ($entry) => array_filter([$entry->series?->title ?? (is_array($entry->tags) ? ($entry->tags[0] ?? null) : null)]);
@endphp

{{-- Built from the components the start page already uses (x-layout.list + x-card.item-card),
     not from the news index's card. The start page carries no imagery at all — a block with
     a large lead picture read as a piece of a different site. --}}
@if($articles->isNotEmpty())
    <x-layout.section>
        {{-- No «read all» link here: the next-page card at the foot of the start page
             already leads to the overview, and two of them side by side read as two
             different destinations. --}}
        <x-h2 :title="__('News')"/>

        <x-layout.list class="mt-4">
            @foreach($articles as $entry)
                <x-card.item-card
                        :url="$urlFor($entry)"
                        :title="$entry->title"
                        :teaser="$entry->teaser"
                        :tags="$topicFor($entry)"
                        :published-at="$entry->published_at"
                        :level="3"/>
            @endforeach
        </x-layout.list>
    </x-layout.section>
@endif
