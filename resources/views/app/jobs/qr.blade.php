<!DOCTYPE html>
<html lang="de-CH">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#FFFFFF">
    <meta name="robots" content="noindex, nofollow"/>
    <title>codebar – Praktikum 27/28</title>
    @vite(['resources/js/kiosk.js'])
</head>
<body>

<main class="qrpage">
    <img class="wordmark" src="{{ asset('images/logos/codebar-logo-colored.svg') }}" alt="codebar" width="565" height="120">

    <h1><mark>Praktikum</mark> 27/28</h1>

    <div class="window">
        <input type="radio" name="qr-tab" id="tab-p" checked>
        <input type="radio" name="qr-tab" id="tab-b">

        <div class="titlebar">
            <span class="dots" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>codebar Solutions AG</span>
        </div>

        <div class="tabbar">
            <label for="tab-p"><b>1</b> praktikum</label>
            <label for="tab-b"><b>2</b> bewerben</label>
        </div>

        <div class="panes">
            <div class="pane pane--p">
                <div class="code">
                    <img src="{{ asset('images/qr/praktikum.svg') }}" alt="QR-Code: codebar.ch/stellen/praktikum" width="580" height="580">
                </div>
                <p class="url">&rarr; codebar.ch/stellen/praktikum</p>
            </div>
            <div class="pane pane--b">
                <div class="code">
                    <img src="{{ asset('images/qr/praktikum-bewerben.svg') }}" alt="QR-Code: codebar.ch/stellen/praktikum#bewerbung" width="580" height="580">
                </div>
                <p class="url">&rarr; codebar.ch/stellen/praktikum#bewerbung</p>
            </div>
        </div>
    </div>
</main>

</body>
</html>
