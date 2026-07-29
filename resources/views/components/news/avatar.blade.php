@props(['image', 'px' => 96])

@php
    use App\Support\CloudinaryUrl;
@endphp

{{-- The author portrait, in the three places an article shows one: the card meta
     line, the byline under the hero and the box at the foot of the article. Only
     the display size differs, and that comes in as a class. --}}
@if(filled($image))
    <img src="{{ CloudinaryUrl::src($image, $px) }}"
         srcset="{{ CloudinaryUrl::srcset($image, $px) }}"
         alt="" width="{{ $px }}" height="{{ $px }}" loading="lazy" decoding="async"
         {{ $attributes->merge(['class' => 'shrink-0 rounded-full bg-surface object-cover']) }}>
@endif
