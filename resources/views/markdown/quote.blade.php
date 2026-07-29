<blockquote class="news-block news-quote">
    <p class="news-quote__text">«{{ $text }}»</p>
    @if(! empty($attributes['cite']))
        <cite class="news-quote__cite">{{ $attributes['cite'] }}</cite>
    @endif
</blockquote>
