@props(['image', 'px' => 96, 'radius' => 'rounded-panel'])

@php
    use App\Support\CloudinaryUrl;
@endphp

{{-- The author portrait, in the three places an article shows one: the card meta
     line, the byline under the hero and the box at the foot of the article. Only
     the display size differs, and that comes in as a class.

     Square with the panel radius, like the team and network cards: a person had two
     different shapes depending on which page you met them on. The tiny meta-line
     portrait takes a smaller radius — at 24px the panel radius is a circle again. --}}
@if(filled($image))
    <img src="{{ CloudinaryUrl::src($image, $px) }}"
         srcset="{{ CloudinaryUrl::srcset($image, $px) }}"
         alt="" width="{{ $px }}" height="{{ $px }}" loading="lazy" decoding="async"
         {{ $attributes->merge(['class' => 'shrink-0 '.$radius.' bg-surface object-cover']) }}>
@endif
