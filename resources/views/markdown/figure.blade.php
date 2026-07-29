@php
    use App\Support\NewsImage;

    $src = $attributes['src'] ?? null;
    $max = match ($width) { 'full' => 1920, 'wide' => 1280, default => 960 };
    $url = NewsImage::src($src, $max);
    $sizes = match ($width) {
        'full' => '100vw',
        'wide' => '(min-width: 1100px) 1040px, 100vw',
        default => '(min-width: 768px) 672px, 100vw',
    };
@endphp

@if($url)
    <figure class="news-block news-block--{{ $width }}">
        <img src="{{ $url }}"
             @if($srcset = NewsImage::srcset($src, $max)) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
             alt="{{ $attributes['alt'] ?? '' }}"
             loading="lazy"
             decoding="async"
             class="w-full {{ $width === 'text' ? '' : 'object-cover' }}">
        @if(trim(strip_tags($body)) !== '')
            <figcaption class="news-caption">{!! $body !!}</figcaption>
        @endif
    </figure>
@endif
