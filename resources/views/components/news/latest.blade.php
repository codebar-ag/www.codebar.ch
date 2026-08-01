@props(['articles' => null])

@php
    $articles ??= collect();
@endphp

{{-- The news index's own list, not a look-alike built from x-card.item-card: one component
     means the two blocks cannot drift apart, down to which side the drawing sits on.
     See prompts/illustration-news-card.md. --}}
@if($articles->isNotEmpty())
    <x-layout.section>
        {{-- No «read all» link here: the next-page card at the foot of the start page
             already leads to the overview, and two of them side by side read as two
             different destinations. --}}
        <x-h2 :title="__('News')"/>

        <x-news.list :articles="$articles"/>
    </x-layout.section>
@endif
