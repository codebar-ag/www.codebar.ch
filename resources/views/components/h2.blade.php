@props(['title'])

{{-- One colour for every heading, a step darker than the body's gray-800. h1, h2, h3 and
     the article's own headings in .news-prose all sit on gray-900; they used to sit on
     three different values.

     mb-4, and the call site adds nothing. The gap under a heading was mb-2 here plus
     whatever the next element brought: nothing on the legal pages (8px), mt-2 on the
     about-us grids (8px), mt-4 on media, explore, next-page and the news lists (16px). One
     number here means a heading binds to its content the same way on every page. --}}
<h2 {{ $attributes->merge(['class' => 'mb-4 text-heading font-semibold text-balance text-gray-900']) }}>{{ $title }}</h2>
