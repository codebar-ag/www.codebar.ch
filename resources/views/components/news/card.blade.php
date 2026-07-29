@props([
    'url',
    'title',
    'teaser' => null,
    'image' => null,
    'kicker' => null,
    'publishedAt' => null,
    'readingMinutes' => null,
    'authorName' => null,
    'authorImage' => null,
    'level' => 2,
    'lead' => false,
    'compact' => false,
])

@php
    use App\Support\NewsImage;

    $heading = 'h'.$level;
    $imageWidth = $lead ? 1280 : 640;
@endphp

<article {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ $url }}"
       class="block cursor-pointer rounded-panel transition focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand {{ $lead || $compact ? '' : 'flex items-start gap-8' }}">

        {{-- Compact: the picture leads, as in the «continue reading» row. --}}
        @if($compact && $src = NewsImage::src($image, $imageWidth))
            <img src="{{ $src }}"
                 @if($srcset = NewsImage::srcset($image, $imageWidth)) srcset="{{ $srcset }}" sizes="(min-width: 640px) 33vw, 100vw" @endif
                 alt="" width="1800" height="600" loading="lazy" decoding="async"
                 class="mb-3 aspect-[4/3] w-full rounded-panel object-cover">
        @endif

        <div class="{{ $lead || $compact ? '' : 'flex-1' }}">
            {{-- A 3:1 band collapses into a strip on a phone, so the crop opens up as
                 the viewport narrows — the same ladder the article hero uses. --}}
            @if($lead && $src = NewsImage::src($image, $imageWidth))
                <img src="{{ $src }}"
                     @if($srcset = NewsImage::srcset($image, $imageWidth)) srcset="{{ $srcset }}" sizes="(min-width: 960px) 896px, 100vw" @endif
                     alt="" width="1800" height="600" decoding="async"
                     class="mb-6 aspect-[4/3] w-full object-cover sm:aspect-[16/9] lg:aspect-[3/1]">
            @endif

            <{{ $heading }} class="{{ $lead ? 'text-display' : ($compact ? 'text-subheading' : 'text-title') }} leading-snug transition group-hover:text-brand">
                {{ $title }}
            </{{ $heading }}>

            @if($teaser)
                <p class="mt-2 {{ $lead ? 'text-lead' : 'text-lg' }} leading-relaxed text-gray-600">{{ $teaser }}</p>
            @endif

            {{-- Every piece of metadata on one line. Splitting author and date above the
                 title from the reading time below it read as two unrelated captions, and
                 the topic sitting above the title as a kicker read as a second heading —
                 as a chip it belongs to the caption, the same way the start page shows it. --}}
            <div class="{{ $compact ? 'mt-2' : 'mt-4' }} flex flex-wrap items-center gap-x-2 gap-y-2 text-sm text-muted">
                @if($kicker)
                    <x-ui.badge :label="$kicker" size="xs"/>
                @endif

                @if($authorName)
                    <x-news.avatar :image="$authorImage" :px="96" class="me-1 size-6"/>
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
        </div>

        @if(! $lead && ! $compact && $src = NewsImage::src($image, $imageWidth))
            <img src="{{ $src }}"
                 @if($srcset = NewsImage::srcset($image, $imageWidth)) srcset="{{ $srcset }}" sizes="176px" @endif
                 alt="" width="1800" height="600" loading="lazy" decoding="async"
                 class="hidden aspect-[4/3] w-44 shrink-0 rounded-panel object-cover sm:block">
        @endif
    </a>
</article>
