@props(['paginator'])

@php
    use Illuminate\Pagination\UrlWindow;

    $window = UrlWindow::make($paginator);

    $elements = array_filter([
        $window['first'],
        is_array($window['slider']) ? '…' : null,
        $window['slider'],
        is_array($window['last']) ? '…' : null,
        $window['last'],
    ]);

    $step = 'inline-flex min-h-control items-center justify-center rounded-pill border px-4 text-sm font-medium transition focus-ring';
    $stepIdle = 'border-border bg-white text-gray-800 hover:border-brand hover:text-brand';
    $stepDisabled = 'border-border-soft bg-white text-hint cursor-not-allowed';

    $page = 'inline-flex min-h-control min-w-control items-center justify-center rounded-pill px-3 text-sm font-medium transition focus-ring';
    $pageIdle = 'text-muted hover:bg-surface hover:text-brand';
    $pageCurrent = 'bg-brand text-white';
@endphp

@if($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination') }}"
         {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between']) }}>

        <p class="text-sm text-muted">
            {{ __('Page :current of :last', ['current' => $paginator->currentPage(), 'last' => $paginator->lastPage()]) }}
        </p>

        <div class="flex flex-wrap items-center gap-2">
            @if($paginator->onFirstPage())
                <span class="{{ $step }} {{ $stepDisabled }}" aria-disabled="true">{{ __('Previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="{{ $step }} {{ $stepIdle }}">{{ __('Previous') }}</a>
            @endif

            @foreach($elements as $element)
                @if(is_string($element))
                    <span class="px-1 text-sm text-hint" aria-hidden="true">{{ $element }}</span>
                @endif

                @if(is_array($element))
                    @foreach($element as $number => $url)
                        @if($number == $paginator->currentPage())
                            <span class="{{ $page }} {{ $pageCurrent }}" aria-current="page">{{ $number }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $page }} {{ $pageIdle }}"
                               aria-label="{{ __('Go to page :page', ['page' => $number]) }}">{{ $number }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="{{ $step }} {{ $stepIdle }}">{{ __('Next') }}</a>
            @else
                <span class="{{ $step }} {{ $stepDisabled }}" aria-disabled="true">{{ __('Next') }}</span>
            @endif
        </div>
    </nav>
@endif
