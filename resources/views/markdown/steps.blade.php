@if(! empty($items))
    <ol class="news-block news-steps">
        @foreach($items as $item)
            <li class="news-steps__item">
                <span class="news-steps__number" aria-hidden="true">{{ $loop->iteration }}</span>
                <div>
                    @if(! empty($item['title']))
                        <p class="news-steps__title">{{ $item['title'] }}</p>
                    @endif
                    @if(! empty($item['body']))
                        <p class="news-steps__body">{{ $item['body'] }}</p>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
@endif
