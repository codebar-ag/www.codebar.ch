<!DOCTYPE html>
<html lang="de-CH">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#FFFFFF">
    <meta name="robots" content="noindex, nofollow"/>
    <title>codebar – IMS-Praktikum 27/28</title>
    @vite(['resources/js/kiosk.js'])
</head>
<body>

<main class="qrpage">
    <img class="wordmark" src="{{ asset('images/logos/codebar-logo-colored.svg') }}" alt="codebar" width="565" height="120">

    <h1><mark>IMS-Praktikum</mark> 27/28</h1>

    <div class="window">
        <div class="titlebar">
            <span class="dots" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>codebar Solutions AG</span>
        </div>

        <div class="tabbar">
            <span class="tab is-active"><b>1</b> praktikum</span>
        </div>

        <div class="panes">
            <div class="code">
                <img src="{{ localized_route('jobs.internship.qr.image') }}" alt="QR-Code: {{ Str::after(localized_route('jobs.internship.show'), '://') }}" width="580" height="580">
            </div>
            <p class="url">&rarr; {{ Str::after(localized_route('jobs.internship.show'), '://') }}</p>
        </div>
    </div>
</main>

</body>
</html>
