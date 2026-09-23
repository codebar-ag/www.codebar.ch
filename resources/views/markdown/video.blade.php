@php
    $src = $attributes['src'] ?? null;
    $title = $attributes['title'] ?? __('Play video');
@endphp

@if($src)
    <figure class="news-block news-block--{{ $width === 'text' ? 'wide' : $width }}">
        <div class="overflow-hidden rounded-panel bg-gray-900">
            <iframe src="{{ $src }}" title="{{ $title }}" class="aspect-video w-full" loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        @if(trim(strip_tags($body)) !== '')
            <figcaption class="news-caption">{!! $body !!}</figcaption>
        @endif
    </figure>
@endif
