@php
    use App\Support\NewsImage;

    // Click-to-load: no request leaves the page towards the video host until the
    // visitor asks for it, so no consent banner is needed for an embedded video.
    $src = $attributes['src'] ?? null;
    $poster = NewsImage::src($attributes['poster'] ?? null, 1280);
    $title = $attributes['title'] ?? __('Play video');
@endphp

@if($src)
    <figure class="news-block news-block--{{ $width === 'text' ? 'wide' : $width }}">
        <div class="relative overflow-hidden rounded-panel bg-gray-900" x-data="videoEmbed" data-src="{{ $src }}">
            <template x-if="!loaded">
                <button type="button" @click="load"
                        class="group relative block aspect-video w-full cursor-pointer focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-white">
                    @if($poster)
                        <img src="{{ $poster }}" alt="" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover opacity-70">
                    @endif
                    <span class="relative grid size-full place-items-center">
                        <span class="grid size-16 place-items-center rounded-full bg-white/90 transition group-hover:bg-white">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="size-7 translate-x-0.5 text-brand" aria-hidden="true">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </span>
                        <span class="sr-only">{{ $title }}</span>
                    </span>
                </button>
            </template>
            <template x-if="loaded">
                <iframe x-bind:src="embedSrc" title="{{ $title }}" class="aspect-video w-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </template>
        </div>
        @if(trim(strip_tags($body)) !== '')
            <figcaption class="news-caption">{!! $body !!}</figcaption>
        @endif
    </figure>
@endif
