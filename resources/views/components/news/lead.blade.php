@props([
    'url',
    'title',
    'teaser' => null,
    'image' => null,
    'topics' => [],
    'publishedAt' => null,
    'readingMinutes' => null,
    'authorName' => null,
    'authorImage' => null,
    'level' => 2,
])

@php
    use App\Support\NewsImage;

    $heading = 'h'.$level;
@endphp

<article {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ $url }}"
       class="block cursor-pointer rounded-panel transition focus-ring-wide">

        @if($src = NewsImage::src($image, 1280))
            <img src="{{ $src }}"
                 @if($srcset = NewsImage::srcset($image, 1280)) srcset="{{ $srcset }}" sizes="(min-width: 960px) 896px, 100vw" @endif
                 alt="" width="1800" height="600" decoding="async"
                 class="mb-6 hidden aspect-[16/9] w-full object-cover sm:block lg:aspect-[3/1]">
        @endif

        <{{ $heading }} class="text-display font-bold text-gray-900 transition group-hover:text-brand">
            {{ $title }}
        </{{ $heading }}>

        @if($teaser)
            <p class="mt-2 text-lead font-light text-gray-600">{{ $teaser }}</p>
        @endif

        <x-news.card-meta :topics="$topics" :published-at="$publishedAt" :reading-minutes="$readingMinutes"
                          :author-name="$authorName" :author-image="$authorImage"/>
    </a>
</article>
