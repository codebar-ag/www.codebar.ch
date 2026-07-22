@props(['title', 'intro' => null])

<x-h2 :title="$title"/>
@if(filled($intro))
    <p class="mb-4 text-muted">{{ $intro }}</p>
@endif
