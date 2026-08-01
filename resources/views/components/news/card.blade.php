@props([
    'url',
    'title',
    'teaser' => null,
    'thumb' => null,
    'topics' => [],
    'publishedAt' => null,
    'readingMinutes' => null,
    'authorName' => null,
    'authorImage' => null,
    'level' => 2,
    'side' => 'right',
])

@php
    use App\Support\NewsImage;

    $heading = 'h'.$level;

    $art = NewsImage::src($thumb, 640);

    $titleClass = $level >= 3 ? 'text-subheading font-semibold' : 'text-title font-semibold';
@endphp

<x-illustration-row :illustration="$art" :side="$side" {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ $url }}"
       class="block cursor-pointer rounded-panel transition focus-ring-wide">

        <{{ $heading }} class="{{ $titleClass }} text-gray-900 transition group-hover:text-brand">
            {{ $title }}
        </{{ $heading }}>

        @if($teaser)
            <p class="mt-2 text-lg leading-relaxed text-gray-600">{{ $teaser }}</p>
        @endif

        <x-news.card-meta :topics="$topics" :published-at="$publishedAt" :reading-minutes="$readingMinutes"
                          :author-name="$authorName" :author-image="$authorImage"/>
    </a>
</x-illustration-row>
