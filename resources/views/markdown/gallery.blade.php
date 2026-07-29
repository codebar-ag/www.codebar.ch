@php
    use App\Support\NewsImage;

    $columns = (int) ($attributes['cols'] ?? 2);
    $columns = in_array($columns, [2, 3], true) ? $columns : 2;
@endphp

@if(! empty($items))
    <figure class="news-block news-block--{{ $width === 'text' ? 'wide' : $width }}">
        <div class="grid gap-4 {{ $columns === 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }}">
            @foreach($items as $item)
                @php($url = NewsImage::src($item['src'] ?? null, 960))
                @if($url)
                    <div>
                        <img src="{{ $url }}"
                             @if($srcset = NewsImage::srcset($item['src'] ?? null, 960)) srcset="{{ $srcset }}" sizes="(min-width: 640px) 33vw, 100vw" @endif
                             alt="{{ $item['alt'] ?? '' }}"
                             loading="lazy" decoding="async"
                             class="w-full aspect-[4/3] object-cover rounded-panel">
                        @if(! empty($item['caption']))
                            <p class="news-caption">{{ $item['caption'] }}</p>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        @if(! empty($attributes['caption']))
            <figcaption class="news-caption">{{ $attributes['caption'] }}</figcaption>
        @endif
    </figure>
@endif
