@props([
    'authorName' => null,
    'authorRole' => null,
    'authorImage' => null,
])

{{-- Author only. Date and reading time live in the kicker line above the title — having
     them here as well made this row read as a crowded two-column table. --}}
@if($authorName)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
        <x-news.avatar :image="$authorImage" :px="96" class="size-11"/>

        <div class="text-sm leading-snug">
            <p class="font-medium text-gray-900">{{ $authorName }}</p>
            @if($authorRole)
                <p class="text-muted">{{ $authorRole }}</p>
            @endif
        </div>
    </div>
@endif
