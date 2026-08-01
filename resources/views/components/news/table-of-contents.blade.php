@props(['headings' => []])

@if(count($headings) > 1)
    <nav x-data="tableOfContents" class="news-toc" aria-labelledby="toc-label">
        <h2 id="toc-label" class="news-toc__title">{{ __('Contents') }}</h2>

        <ul class="news-toc__list">
            @foreach($headings as $heading)
                <li class="{{ $heading['level'] === 3 ? 'news-toc__sub' : '' }}">
                    <a href="#{{ $heading['id'] }}" data-anchor="{{ $heading['id'] }}">{{ $heading['title'] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
