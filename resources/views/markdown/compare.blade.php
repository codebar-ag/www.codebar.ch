@php
    use App\Support\NewsImage;

    $pair = array_slice($items, 0, 2);
@endphp

@if(count($pair) === 2)
    <figure class="news-block news-block--{{ $width === 'text' ? 'wide' : $width }}">
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach($pair as $item)
                @php($url = NewsImage::src($item['src'] ?? null, 960))
                @if($url)
                    <div class="overflow-hidden rounded-panel border border-border">
                        <img src="{{ $url }}"
                             @if($srcset = NewsImage::srcset($item['src'] ?? null, 960)) srcset="{{ $srcset }}" sizes="(min-width: 640px) 50vw, 100vw" @endif
                             alt="{{ $item['alt'] ?? '' }}"
                             loading="lazy" decoding="async"
                             class="w-full aspect-[4/3] object-cover">
                        <p class="bg-surface px-4 py-2 text-sm text-muted">
                            {{ $item['caption'] ?? ($loop->first ? __('Before') : __('After')) }}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
        @if(! empty($attributes['caption']))
            <figcaption class="news-caption">{{ $attributes['caption'] }}</figcaption>
        @endif
    </figure>
@endif
