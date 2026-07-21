@props(['url', 'title', 'teaser', 'tags' => []])

<a href="{{ $url }}" class="group block py-4 transition hover:bg-gray-50/50 rounded">
    <div class="flex flex-col gap-1">
        <div class="text-xl font-semibold text-gray-800 group-hover:text-brand">
            {{ $title }}
        </div>
        <div class="text-gray-600">
            {{ $teaser }}
        </div>

        @php
            $tags = collect($tags);
        @endphp

        @if($tags->isNotEmpty())
            <div class="mt-1 flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <x-badge label="{{ $tag }}" class-attributes="text-xs"/>
                @endforeach
            </div>
        @endif

        <span class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-brand">
            {{ __('Learn more') }}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </span>
    </div>
</a>
