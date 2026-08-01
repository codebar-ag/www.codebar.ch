@props([
    'articles' => null,
    'level' => 3,
    'rule' => false,
])

@php
    $articles ??= collect();
    $urlFor = fn ($entry) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry]);
@endphp

{{-- The one news list on the site: the index below its lead, the start page, and
     «Continue reading» at the foot of an article all render this. The alternating side of
     the drawing lives here and nowhere else — three call sites juggling their own $loop is
     how two of them ended up out of step in the first place. --}}
{{-- Every row keeps symmetric padding — the drawing is centred on the row box, and a row
     that gave up its top padding would centre its drawing lower than the rest, which is
     the one illustration in the list that stops lining up with its own title.

     So the list pulls itself up by exactly the padding of its first row instead. Under a
     heading that leaves the heading's own mb-4 as the whole gap, and the rows go on
     spacing themselves. rule is the other opening: the news index, where a hairline
     separates the list from the featured article above it — there the first row's padding
     is the air the line needs, and nothing is pulled back. --}}
<x-layout.list {{ $attributes->class([
    'border-t border-border' => $rule,
    '-mt-8 sm:-mt-10 lg:-mt-12' => ! $rule,
]) }}>
    @foreach($articles as $entry)
        <x-news.card
                :side="$loop->odd ? 'right' : 'left'"
                :url="$urlFor($entry)"
                :title="$entry->title"
                :teaser="$entry->teaser"
                :thumb="$entry->thumb_image"
                :topics="$entry->topics()"
                :published-at="$entry->published_at"
                :reading-minutes="$entry->reading_minutes"
                :author-name="$entry->authorName()"
                :author-image="$entry->authorContact?->image"
                :level="$level"/>
    @endforeach
</x-layout.list>
