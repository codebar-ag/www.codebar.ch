@php
    $urlFor = fn ($entry) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry]);
    $indexUrl = localized_route('news.index');
@endphp

<x-app-layout :page="$page" :schema="$schema" :preconnect-cloudinary="true">
    <x-layout.page-header
            :title="__('News')"
            :intro="__('Insights from our day-to-day work: what we are building, what we are learning, and what is happening at codebar.')"/>

    {{--
    @if($topics->isNotEmpty())
        <x-layout.section class="mt-10!">
            <div class="flex flex-col gap-3 border-b-2 border-border sm:flex-row sm:items-end sm:justify-between sm:gap-6">
                <nav class="-mb-0.5 flex flex-wrap gap-x-7" aria-label="{{ __('Filter by topic') }}">
                    <a href="{{ $indexUrl }}"
                       @if($activeTopic === null) aria-current="page" @endif
                       class="inline-flex tap-target items-center border-b-2 pb-3 transition focus-ring {{ $activeTopic === null ? 'border-brand font-semibold text-brand' : 'border-transparent text-muted hover:border-gray-300 hover:text-gray-900' }}">
                        {{ __('All') }}
                    </a>

                    @foreach($topics as $topic)
                        @php($isActive = $activeTopic !== null && $activeTopic->is($topic))
                        <a href="{{ $indexUrl }}?thema={{ $topic->slug }}"
                           @if($isActive) aria-current="page" @endif
                           class="inline-flex tap-target items-center border-b-2 pb-3 transition focus-ring {{ $isActive ? 'border-brand font-semibold text-brand' : 'border-transparent text-muted hover:border-gray-300 hover:text-gray-900' }}">
                            {{ $topic->title }}
                        </a>
                    @endforeach
                </nav>

                <p class="pb-3 text-sm whitespace-nowrap text-muted">
                    @if($activeTopic !== null)
                        {{ trans_choice(':count news item on :topic|:count news items on :topic', $total, ['count' => $total, 'topic' => $activeTopic->title]) }}
                    @else
                        {{ trans_choice(':count news item|:count news items', $total, ['count' => $total]) }}
                    @endif
                </p>
            </div>
        </x-layout.section>
    @endif
    --}}

    @if($lead)
        <x-layout.section>
            <x-news.lead
                    :url="$urlFor($lead)"
                    :title="$lead->title"
                    :teaser="$lead->teaser"
                    :image="$lead->hero_image"
                    :topics="$lead->topics()"
                    :published-at="$lead->published_at"
                    :reading-minutes="$lead->reading_minutes"
                    :author-name="$lead->authorName()"
                    :author-image="$lead->authorContact?->image"
                    :level="2"/>
        </x-layout.section>
    @endif

    @if($news->isNotEmpty())
        <x-layout.section>
            <x-news.list :articles="$news" :level="2" :rule="true"/>
        </x-layout.section>
    @endif

    @if($lead === null)
        <x-layout.section>
            <p class="text-lg text-muted">{{ __('No news on this topic yet.') }}</p>
        </x-layout.section>
    @endif
</x-app-layout>
