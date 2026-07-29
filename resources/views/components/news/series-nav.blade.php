@props(['series', 'parts', 'current', 'urlFor'])

@php
    $total = $parts->count();
    $position = $parts->search(fn ($part) => $part->is($current));
    $position = $position === false ? null : $position;
    $previous = $position !== null && $position > 0 ? $parts[$position - 1] : null;
    $next = $position !== null && $position < $total - 1 ? $parts[$position + 1] : null;
@endphp

<section class="news-block border-t border-border pt-10" aria-labelledby="series-heading">
    <p class="text-sm uppercase tracking-[0.15em] text-muted">
        {{ __('Series') }}
        @if($position !== null)
            <span aria-hidden="true">·</span>
            {{ __('Part :position of :total', ['position' => $position + 1, 'total' => $total]) }}
        @endif
    </p>
    <x-h2 id="series-heading" :title="$series->title" class="mt-2 mb-0 text-title"/>
    @if($series->description)
        <p class="mt-2 max-w-2xl text-lg leading-relaxed text-gray-600">{{ $series->description }}</p>
    @endif

    <div class="mt-6 flex gap-1.5" aria-hidden="true">
        @foreach($parts as $part)
            <span class="h-1 flex-1 rounded-pill {{ $position !== null && $loop->index <= $position ? 'bg-brand' : 'bg-border' }}"></span>
        @endforeach
    </div>

    <ol class="mt-8 divide-y divide-border-soft border-y border-border-soft">
        @foreach($parts as $part)
            @php($isCurrent = $part->is($current))
            <li class="flex items-center gap-4 py-4">
                <span @class([
                    'shrink-0 size-8 rounded-full grid place-items-center text-sm font-semibold',
                    'bg-brand text-white' => $isCurrent,
                    'border border-brand text-brand' => ! $isCurrent && $position !== null && $loop->index < $position,
                    'border border-border text-muted' => ! $isCurrent && ($position === null || $loop->index > $position),
                ])>{{ $loop->iteration }}</span>

                @if($isCurrent)
                    <span class="text-lg font-semibold text-gray-900" aria-current="true">{{ $part->title }}</span>
                @else
                    <x-ui.link :href="$urlFor($part)" :label="$part->title" class="text-lg text-gray-700"/>
                @endif

                @if($part->reading_minutes)
                    <span class="ml-auto text-sm text-muted">{{ __(':count min', ['count' => $part->reading_minutes]) }}</span>
                @endif
            </li>
        @endforeach
    </ol>

    @if($previous || $next)
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            @if($previous)
                <x-card.linkable :url="$urlFor($previous)" surface="panel-lg">
                    <p class="text-sm text-muted">← {{ __('Part :position', ['position' => $position]) }}</p>
                    <p class="mt-1 text-subheading transition group-hover:text-brand">{{ $previous->title }}</p>
                </x-card.linkable>
            @else
                <div></div>
            @endif

            @if($next)
                <x-card.linkable :url="$urlFor($next)" surface="panel-lg" class="text-right">
                    <p class="text-sm text-muted">{{ __('Part :position', ['position' => $position + 2]) }} →</p>
                    <p class="mt-1 text-subheading transition group-hover:text-brand">{{ $next->title }}</p>
                </x-card.linkable>
            @endif
        </div>
    @endif
</section>
