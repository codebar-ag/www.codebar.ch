<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $statusCode }} — {{ $title }} — {{ config('site.company') }}</title>
    <style>
        :root { color-scheme: light dark; }
        html, body { margin: 0; padding: 0; height: 100%; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            color: #1d1d1f;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        @media (prefers-color-scheme: dark) {
            body { background: #0a0a0a; color: #f5f5f7; }
            a { color: #9bb6ff; }
        }
        .panel { max-width: 32rem; width: 100%; text-align: center; }
        .status { font-size: .8125rem; letter-spacing: .04em; color: #6b7280; font-variant-numeric: tabular-nums; }
        h1 { font-size: clamp(1.5rem, 4vw, 2rem); margin: .5rem 0 1rem; font-weight: 600; }
        p { color: #4b5563; margin: 0 0 1.5rem; }
        @media (prefers-color-scheme: dark) { p, .status { color: #a1a1aa; } }
        a.button {
            display: inline-block;
            padding: .65rem 1.25rem;
            background: #500472;
            color: #fff;
            border-radius: .5rem;
            text-decoration: none;
            font-weight: 500;
        }
        a.button:hover { background: #3a0354; }
    </style>
</head>
<body>
    <div class="panel">
        <p class="status">{{ $statusCode }}</p>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a class="button" href="{{ url('/') }}">{{ __('errors.back_home') }}</a>
    </div>
</body>
</html>
