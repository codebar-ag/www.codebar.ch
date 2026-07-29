@php
    use App\Support\NewsImage;

    $heroSrc = NewsImage::src($news->hero_image, 1280);
    $seriesUrl = fn ($part) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $part]);
@endphp

<x-app-layout :page="$page" :schema="$schema" :wide="true" :preconnect-cloudinary="true">

    {{-- Reading progress --}}
    <div x-data="readingProgress" class="fixed inset-x-0 top-0 z-40 h-0.5 bg-transparent" aria-hidden="true">
        <div x-ref="bar" class="h-full bg-brand transition-[width] duration-150" style="width: 0"></div>
    </div>

    <article>
        {{-- Title block first, image after: the reading order of «Quiet Reader» --}}
        <div class="news-body">
            <x-breadcrumbs :items="[
                ['label' => __('News'), 'url' => localized_route('news.index')],
                ['label' => $title],
            ]"/>

            {{-- Category, date and reading time on one line — the byline below carries
                 nothing but the author. --}}
            <p class="flex flex-wrap items-center gap-x-2 text-sm">
                @if($news->series)
                    <span class="uppercase tracking-[0.15em] text-brand">
                        {{ $news->series->title }}@if($news->series_position), {{ __('Part :position', ['position' => $news->series_position]) }}@endif
                    </span>
                @elseif($tags->isNotEmpty())
                    <span class="uppercase tracking-[0.15em] text-brand">{{ $tags->first() }}</span>
                @endif

                @if($news->published_at)
                    <span class="text-muted" aria-hidden="true">·</span>
                    <time class="text-muted" datetime="{{ $news->published_at->toDateString() }}">
                        {{ $news->published_at->translatedFormat('j. F Y') }}
                    </time>
                @endif

                @if($news->reading_minutes)
                    <span class="text-muted" aria-hidden="true">·</span>
                    <span class="text-muted">{{ __(':count min read', ['count' => $news->reading_minutes]) }}</span>
                @endif
            </p>

            {{-- The same h1 and the same lead as every other page on the site. --}}
            <x-h1 :title="$title" class="mt-4 mb-0"/>

            @if($teaser)
                <p class="mt-4 max-w-3xl text-lead font-light text-gray-800">{{ $teaser }}</p>
            @endif
        </div>

        {{-- Hero, then the author. The aspect ratio tightens with the viewport so a 3:1
             band does not collapse into a strip on a phone. --}}
        @if($heroSrc)
            <div class="news-body mt-10">
                <figure class="m-0">
                    <img src="{{ NewsImage::src($news->hero_image, 1280) }}"
                         @if($srcset = NewsImage::srcset($news->hero_image, 1280)) srcset="{{ $srcset }}" sizes="(min-width: 960px) 896px, 100vw" @endif
                         alt="{{ $news->hero_alt ?: '' }}"
                         width="1800" height="600" fetchpriority="high" decoding="async"
                         class="w-full aspect-[4/3] sm:aspect-[16/9] lg:aspect-[3/1] object-cover">
                    @if($news->hero_caption)
                        <figcaption class="news-caption">{{ $news->hero_caption }}</figcaption>
                    @endif
                </figure>
            </div>
        @endif

        <div class="news-body {{ $heroSrc ? 'mt-8' : 'mt-10' }}">
            <x-news.byline class="border-t border-border pt-6"
                           :author-name="$authorName"
                           :author-role="$authorRole"
                           :author-image="$authorImage"/>
        </div>

        {{-- The table of contents is a band above the article, spanning the full content
             width. Nothing sits beside the reading column. --}}
        <div class="news-body mt-14">
            <x-news.table-of-contents :headings="$headings"/>
        </div>

        {{-- The article body. Markdown directives render their own block components. --}}
        <div id="article-body" class="news-body news-prose mt-14">
            {!! $content !!}
        </div>

        <div class="news-body mt-16">
            @if($tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 border-t border-border pt-6">
                    @foreach($tags as $tag)
                        <x-ui.badge :label="$tag" variant="outline" size="md"/>
                    @endforeach
                </div>
            @endif

            @if($authorName)
                <div class="mt-12 flex gap-5 border-t border-border-soft pt-8">
                    <x-news.avatar :image="$authorImage" :px="120" class="size-14"/>
                    <div>
                        <p class="text-sm text-muted">{{ __('Written by') }}</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $authorName }}</p>
                        @if($authorRole)
                            <p class="text-sm text-muted">{{ $authorRole }}</p>
                        @endif
                        @if($authorLinkedin)
                            <x-ui.link :href="$authorLinkedin" target="_blank" label="LinkedIn →"
                                       class="mt-2 inline-flex min-h-control items-center text-sm font-medium text-brand hover:underline sm:min-h-0"/>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if($news->series && $seriesParts->count() > 1)
            <div class="news-body mt-20">
                <x-news.series-nav :series="$news->series" :parts="$seriesParts" :current="$news" :url-for="$seriesUrl"/>
            </div>
        @endif

        @if($related->isNotEmpty())
            <div class="news-body mt-20">
                <section class="border-t border-border pt-10">
                    <x-h2 :title="__('Continue reading')"/>
                    <div class="mt-8 grid gap-8 sm:grid-cols-3">
                        @foreach($related as $item)
                            <x-news.card
                                    :compact="true"
                                    :url="localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $item])"
                                    :title="$item->title"
                                    :image="$item->hero_image"
                                    :reading-minutes="$item->reading_minutes"
                                    :level="3"/>
                        @endforeach
                    </div>
                </section>
            </div>
        @endif
    </article>
</x-app-layout>
