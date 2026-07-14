@props(['url', 'image', 'title', 'teaser', 'tags' => []])

@php
    use App\Support\CloudinaryUrl;
@endphp

<a href="{{ $url }}" class="group flex flex-col rounded-xl overflow-hidden ring-1 ring-gray-200 transition hover:shadow-md hover:ring-gray-300">
    <div class="aspect-video w-full overflow-hidden bg-gray-100">
        <img src="{{ CloudinaryUrl::src($image, 640) }}"
             srcset="{{ CloudinaryUrl::srcset($image, 640) }}"
             sizes="(min-width: 1024px) 50vw, 100vw"
             alt="{{ $title }}"
             loading="lazy"
             class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />
    </div>

    <div class="flex flex-col gap-1 p-4">
        <div class="font-semibold text-gray-800 group-hover:text-brand">
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
    </div>
</a>
