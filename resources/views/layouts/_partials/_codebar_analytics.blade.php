@php
    $environments = config('default.services.codebar_analytics.environments', []);
    $url = config('default.services.codebar_analytics.url');
@endphp

@if (in_array(app()->environment(), $environments) && $url)
    <script src="{{ rtrim($url, '/') }}/js/script.js" defer></script>
@endif
