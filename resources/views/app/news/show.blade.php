@php
    $seriesUrl = fn ($part) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $part]);
@endphp

<x-app-layout :page="$page" :schema="$schema" :wide="true" :preconnect-cloudinary="true">

    <div x-data="readingProgress" class="fixed inset-x-0 top-0 z-40 h-0.5 bg-transparent" aria-hidden="true">
        <div x-ref="bar" class="h-full bg-brand transition-[width] duration-150" style="width: 0"></div>
    </div>

    <article>
        <div class="news-body">
            <x-layout.page-header :title="$title" :intro="$teaser" :breadcrumbs="[
                ['label' => __('News'), 'url' => localized_route('news.index')],
            ]">
                <x-slot:meta>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                        @if($news->series)
                            <span class="text-eyebrow uppercase text-brand">
                                {{ $news->series->title }}@if($news->series_position), {{ __('Part :position', ['position' => $news->series_position]) }}@endif
                            </span>
                        @endif

                        @foreach($tags as $tag)
                            <x-ui.badge :label="$tag" size="sm"/>
                        @endforeach

                        <p class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-muted">
                            @if($news->published_at)
                                <time datetime="{{ $news->published_at->toDateString() }}">
                                    @if($news->revised_at)
                                        {{ __('Published :date', ['date' => $news->published_at->translatedFormat('j. F Y')]) }}
                                    @else
                                        {{ $news->published_at->translatedFormat('j. F Y') }}
                                    @endif
                                </time>
                            @endif

                            @if($news->revised_at)
                                @if($news->published_at)
                                    <span aria-hidden="true">·</span>
                                @endif
                                <time datetime="{{ $news->revised_at->toDateString() }}">
                                    {{ __('Updated :date', ['date' => $news->revised_at->translatedFormat('j. F Y')]) }}
                                </time>
                            @endif

                            @if($news->reading_minutes)
                                @if($news->published_at || $news->revised_at)
                                    <span aria-hidden="true">·</span>
                                @endif
                                <span>{{ __(':count min read', ['count' => $news->reading_minutes]) }}</span>
                            @endif
                        </p>
                    </div>
                </x-slot:meta>
            </x-layout.page-header>
        </div>

        <div class="news-body mt-10">
            <x-news.table-of-contents :headings="$headings"/>
        </div>

        <div id="article-body" class="news-body news-prose mt-12">
            {!! $content !!}
        </div>

        @if($authors->isNotEmpty())
            <div class="news-body mt-16">
                <section class="border-t border-border pt-10">
                    <x-h2 :title="$authors->count() > 1 ? __('Authors') : __('Author')"/>
                    <x-layout.grid :cols="2">
                        @foreach($authors as $author)
                            <x-card.person-card
                                    :name="$author->name"
                                    :role="$author->role"
                                    :icons="$author->icons"
                                    :image="$author->image"/>
                        @endforeach
                    </x-layout.grid>
                </section>
            </div>
        @endif

        @if($news->series && $seriesParts->count() > 1)
            <div class="news-body mt-16">
                <x-news.series-nav :series="$news->series" :parts="$seriesParts" :current="$news" :url-for="$seriesUrl"/>
            </div>
        @endif

        @if($related->isNotEmpty())
            <div class="news-body mt-16">
                <section class="border-t border-border pt-10">
                    <x-h2 :title="__('Continue reading')"/>
                    <x-news.list :articles="$related"/>
                </section>
            </div>
        @endif
    </article>
</x-app-layout>
