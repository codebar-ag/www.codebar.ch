@props(['headings' => []])

@if(count($headings) > 1)
    {{-- Aligned with the reading column, not the frame: nothing on this page sticks
         out past the text. --}}
    <nav x-data="tableOfContents" class="news-toc" aria-labelledby="toc-label">
        <p id="toc-label" class="news-toc__label">{{ __('Contents') }}</p>

        <ul class="news-toc__list">
            @foreach($headings as $heading)
                <li class="{{ $heading['level'] === 3 ? 'news-toc__sub' : '' }}">
                    <a href="#{{ $heading['id'] }}" data-anchor="{{ $heading['id'] }}">
                        {{ $heading['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
