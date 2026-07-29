@php
    $type = $attributes['type'] ?? 'info';

    [$classes, $label] = match ($type) {
        'warning' => ['border-amber-200 bg-amber-50 text-amber-950', __('Attention')],
        'tip' => ['border-emerald-200 bg-emerald-50 text-emerald-950', __('Tip')],
        'summary' => ['border-border bg-surface text-gray-800', __('In short')],
        default => ['border-sky-200 bg-sky-50 text-sky-950', __('Note')],
    };

    $title = $attributes['title'] ?? $label;
@endphp

<aside class="news-block news-callout {{ $classes }}" role="note">
    <p class="news-callout__title">{{ $title }}</p>
    <div class="news-callout__body">{!! $body !!}</div>
</aside>
