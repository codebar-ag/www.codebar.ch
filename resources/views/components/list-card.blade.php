@props(['url','title','teaser','tags' => []])

<a href="{{ $url }}" class="group block p-4 transition hover:bg-gray-50/50 hover:shadow-sm rounded">
    <div class="flex flex-col gap-1">
        <div class="font-semibold text-gray-800 group-hover:text-primary">
            {{ $title }}
        </div>
        <div class="text-gray-600">
            {{ $teaser }}
        </div>
        @php
            $tags = collect($tags);
        @endphp

        @if(!empty($tags) && $tags->count())
            <div class="hidden md:flex gap-2">
                @foreach($tags as $tag)
                    <x-badge label="{{ $tag }}" class-attributes="text-xs"/>
                @endforeach
            </div>
        @endif
    </div>
</a>