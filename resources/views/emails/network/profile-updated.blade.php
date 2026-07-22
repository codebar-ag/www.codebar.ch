<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #1f2937; line-height: 1.6;">
    <p>{{ __('Network profile updated: :name', ['name' => $networkUser->name]) }}</p>

    <ul>
        <li>{{ __('Company') }}: {{ $networkUser->network_key }}</li>
        <li>{{ __('Email') }}: {{ $networkUser->email ?? '—' }}</li>
        <li>LinkedIn: {{ $networkUser->linkedin ?? '—' }}</li>
        <li>{{ __('Phone') }}: {{ $networkUser->phone ?? '—' }}</li>
        <li>{{ __('Published') }}: {{ $networkUser->published ? __('Yes') : __('No') }}</li>
        <li>{{ __('Website') }}: {{ $networkUser->network()?->website ?? '—' }}</li>
    </ul>
</body>
</html>
