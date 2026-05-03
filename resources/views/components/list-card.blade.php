@props(['url','title','teaser','tags' => [],'target' => '_self'])

<a target="{{ $target }}" href="{{ $url }}" class="group block">
    <x-ui.card class-attributes="min-h-[150px] editorial-divider">
        <div class="flex flex-col gap-2">
            <h3 class="text-xl font-semibold text-[var(--text-strong)]">
                {{ $title }}
            </h3>
            <p class="text-[var(--text-muted)]">
                {{ $teaser }}
            </p>
        @php
            $tags = collect($tags);
        @endphp

        @if(!empty($tags) && $tags->count())
            <div class="mt-2 hidden md:flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <x-badge label="{{ $tag }}" class-attributes="text-xs"/>
                @endforeach
            </div>
        @endif
        </div>
    </x-ui.card>
</a>