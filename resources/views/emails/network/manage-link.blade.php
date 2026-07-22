<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #1f2937; line-height: 1.6;">
    <p>{{ __('Hello :name', ['name' => $networkUser->name]) }}</p>

    <p>{{ __('You requested a link to update your profile in the codebar network. The link is valid for 48 hours and only applies to your own profile.') }}</p>

    <p>
        <a href="{{ $url }}" style="color: #1f2937; font-weight: bold;">{{ __('Update my profile') }}</a>
    </p>

    <p>{{ __('If you did not request this link, you can ignore this email.') }}</p>

    <p>codebar Solutions AG</p>
</body>
</html>
