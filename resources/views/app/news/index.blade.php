@php
    $urlFor = fn ($entry) => localized_route('news.show', ['locale' => app()->getLocale(), 'news' => $entry]);
    $kickerFor = fn ($entry) => $entry->series?->title ?? (is_array($entry->tags) ? ($entry->tags[0] ?? null) : null);
    $indexUrl = localized_route('news.index');
@endphp

<x-app-layout :page="$page" :schema="$schema" :preconnect-cloudinary="true">
    <x-layout.page-header
            :title="__('News')"
            :intro="__('Insights from our day-to-day work: what we are building, what we are learning, and what is happening at codebar.')"/>

    @if($topics->isNotEmpty())
        <x-layout.section>
            <nav class="flex flex-wrap gap-2" aria-label="{{ __('Filter by topic') }}">
                <x-ui.badge :href="$indexUrl" :label="__('All')" size="md"
                            :variant="$activeTopic === null ? 'solid' : 'outline'"
                            :aria-current="$activeTopic === null ? 'page' : false"/>

                @foreach($topics as $topic)
                    @php($isActive = $activeTopic !== null && $activeTopic->is($topic))
                    <x-ui.badge :href="$indexUrl . '?thema=' . $topic->slug" :label="$topic->title" size="md"
                                :variant="$isActive ? 'solid' : 'outline'"
                                :aria-current="$isActive ? 'page' : false"/>
                @endforeach
            </nav>

            {{-- Rendered in every state, filtered or not, so switching topics never adds
                 or removes a line and the articles below stay where they are. --}}
            <p class="mt-4 text-sm text-muted">
                @if($activeTopic !== null)
                    {{ trans_choice(':count news item on :topic|:count news items on :topic', $total, ['count' => $total, 'topic' => $activeTopic->title]) }}
                @else
                    {{ trans_choice(':count news item|:count news items', $total, ['count' => $total]) }}
                @endif
            </p>
        </x-layout.section>
    @endif

    @if($lead)
        <x-layout.section>
            <x-news.card
                    :lead="true"
                    :url="$urlFor($lead)"
                    :title="$lead->title"
                    :teaser="$lead->teaser"
                    :image="$lead->hero_image"
                    :kicker="$kickerFor($lead)"
                    :published-at="$lead->published_at"
                    :reading-minutes="$lead->reading_minutes"
                    :author-name="$lead->authorName()"
                    :author-image="$lead->authorContact?->image"
                    :level="2"/>
        </x-layout.section>
    @endif

    @if($news->isNotEmpty())
        <x-layout.section>
            <div class="mt-10 divide-y divide-border-soft border-t border-border">
                @foreach($news as $entry)
                    <x-news.card
                            class="py-10"
                            :url="$urlFor($entry)"
                            :title="$entry->title"
                            :teaser="$entry->teaser"
                            :image="$entry->hero_image"
                            :kicker="$kickerFor($entry)"
                            :published-at="$entry->published_at"
                            :reading-minutes="$entry->reading_minutes"
                            :author-name="$entry->authorName()"
                            :author-image="$entry->authorContact?->image"
                            :level="2"/>
                @endforeach
            </div>
        </x-layout.section>
    @endif

    @if($lead === null)
        <x-layout.section>
            <p class="text-lg text-muted">{{ __('No news on this topic yet.') }}</p>
        </x-layout.section>
    @endif
</x-app-layout>
