@props([
    'statusCode' => 500,
    'title' => '',
    'message' => '',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $statusCode }} — {{ $title }}</title>
    <style>
        :root { color-scheme: light dark; }
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 32rem; margin: 4rem auto; padding: 0 1rem; line-height: 1.5; color: #333; }
        @media (prefers-color-scheme: dark) { body { background: #111; color: #ddd; } a { color: #9bf; } }
        .status { font-size: 0.875rem; color: #888; }
        h1 { font-size: 1.75rem; margin: .25rem 0 1rem; }
        a.button { display: inline-block; margin-top: 1.5rem; padding: .6rem 1.2rem; background: #500472; color: #fff; border-radius: .375rem; text-decoration: none; }
    </style>
</head>
<body>
    <p class="status">{{ $statusCode }}</p>
    <h1>{{ $title }}</h1>
    <p>{{ $message }}</p>
    <a class="button" href="{{ url('/') }}">{{ __('errors.back_home') }}</a>
</body>
</html>
