<!DOCTYPE html>
<html lang="de-CH">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="robots" content="noindex, nofollow"/>
    <title>codebar am Stand</title>
    @vite(['resources/js/kiosk.js'])
</head>
<body class="kiosk">

<div class="stage">
    <canvas id="field" aria-hidden="true"></canvas>
    <div class="beam" id="beam" aria-hidden="true"></div>

    <div class="chrome chrome--top">
        <button type="button" class="home" id="home" aria-label="Zurück zur ersten Folie">
            <img src="{{ asset('images/logos/codebar-logo-colored.svg') }}" alt="codebar" width="565" height="120">
        </button>
        <button type="button" class="play" id="play" aria-label="Präsentation pausieren oder fortsetzen">
            <svg class="glyph-pause" viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
            <svg class="glyph-play" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4.5v15a1 1 0 0 0 1.54.84l11.2-7.5a1 1 0 0 0 0-1.68L8.54 3.66A1 1 0 0 0 7 4.5Z"/></svg>
            <span class="txt"></span>
        </button>
    </div>

    <div class="deck" id="deck">

        <section class="slide slide--cover on" data-dur="9000">
            <svg class="bigglyph" viewBox="0 0 299 337" aria-hidden="true"><defs><linearGradient id="cbg" x1="-0.39" y1="-0.56" x2="298.71" y2="337.36" gradientUnits="userSpaceOnUse"><stop stop-color="#C026D3" stop-opacity="0.45"/><stop offset=".5" stop-color="#500472" stop-opacity="0.45"/><stop offset="1" stop-color="#2563EB" stop-opacity="0.4"/></linearGradient></defs><path fill="url(#cbg)" d="M165.982 336.715C67.8054 336.715 0 266.125 0 168.005C0 69.8839 67.8054 0 165.982 0C223.193 0 269.809 25.4125 298.061 65.6485L235.2 114.356C223.899 100.944 204.122 83.2964 168.1 83.2964C119.366 83.2964 86.1695 119.297 86.1695 168.005C86.1695 216.711 119.366 252.713 168.1 252.713C204.122 252.713 223.193 237.889 235.2 223.065L298.061 270.36C269.809 311.302 223.193 336.715 165.982 336.715Z"/></svg>
            <div class="inner">
                <img class="wordmark" data-in style="--d:0" src="{{ asset('images/logos/codebar-logo-colored.svg') }}" alt="codebar" width="565" height="120">
                <div class="rule" data-in style="--d:1"></div>
                <h1 data-in style="--d:2">Wir erwecken Ideen<br><em>zum Leben.</em></h1>
                <p class="lead" data-in style="--d:4">Von der ersten Skizze bis zur Software im täglichen Einsatz — aus der Region Basel.</p>
            </div>
        </section>

        <section class="slide slide--expertise" data-dur="17000">
            <div class="inner">
                <div class="eyebrow" data-in style="--d:0">Expertise</div>
                <h2 data-in style="--d:1">Vier Bereiche, <em>ein Weg.</em></h2>
                <p class="lead lead--wide" data-in style="--d:2">Von der ersten Idee über Konzept und Umsetzung bis zum Betrieb, den wir langfristig begleiten.</p>
                <div class="grid grid--2" data-in style="--d:3">

                    <article class="card">
                        <div class="head">
                            <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 9v12"/><path d="M13 13h4M13 16.5h2.5"/></svg></div>
                            <h3>Konzeption &amp; Prototyping</h3>
                        </div>
                        <p>Mockups und klickbare Prototypen — am Ende steht ein technisches Konzept.</p>
                    </article>

                    <article class="card">
                        <div class="head">
                            <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 7 3.5 12l5 5M15.5 7l5 5-5 5M13.5 4l-3 16"/></svg></div>
                            <h3>Individuelle Software&shy;entwicklung</h3>
                        </div>
                        <p>Portale, Schnittstellen, Automatisierungen — gebaut mit Open Source wie Laravel.</p>
                    </article>

                    <article class="card card--aside">
                        <div class="head">
                            <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h7l4 4v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v4h4M9 13h6M9 16.5h4"/></svg></div>
                            <h3>DMS &amp; ECM</h3>
                        </div>
                        <p>Vom Papier zum papierlosen Büro und weiter zur Automatisierung.</p>
                        <div class="tag">DocuWare Silver &amp; Cloud Partner</div>
                    </article>

                    <article class="card card--aside">
                        <div class="head">
                            <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg></div>
                            <h3>Open Source ERP</h3>
                        </div>
                        <p>Odoo als ERP — Schritt für Schritt, ohne Lizenz-Lock-in.</p>
                        <div class="tag">Odoo-Partner</div>
                    </article>

                </div>
            </div>
        </section>

        <section class="slide" data-dur="16000">
            <div class="inner">
                <div class="eyebrow" data-in style="--d:0">Technologien</div>
                <h2 data-in style="--d:1">Womit wir <em>entwickeln.</em></h2>
                <div class="tools">
                    <div class="tool-row" data-in style="--d:2">
                        <div class="tool-label">Konzipieren</div>
                        <div class="tool-set">
                            <span class="chip">Balsamiq</span>
                            <span class="chip">Figma</span>
                        </div>
                    </div>
                    <div class="tool-row" data-in style="--d:3">
                        <div class="tool-label">Backend</div>
                        <div class="tool-set">
                            <span class="group"><span class="chip chip--core">PHP</span><i class="link"></i><span class="chip">Laravel</span></span>
                        </div>
                    </div>
                    <div class="tool-row" data-in style="--d:4">
                        <div class="tool-label">Frontend</div>
                        <div class="tool-set">
                            <span class="group"><span class="chip chip--core">JavaScript</span><i class="link"></i><span class="chip">Vue</span><span class="chip">Alpine.js</span></span>
                            <span class="group"><span class="chip chip--core">CSS</span><i class="link"></i><span class="chip">Tailwind CSS</span></span>
                        </div>
                    </div>
                    <div class="tool-row" data-in style="--d:5">
                        <div class="tool-label">Mobile</div>
                        <div class="tool-set">
                            <span class="group"><span class="chip chip--core">PHP</span><i class="link"></i><span class="chip">NativePHP</span></span>
                        </div>
                    </div>
                </div>
                <p class="kicker" data-in style="--d:6">Open Source, wo immer es geht — überall weiterverwendbar.</p>
            </div>
        </section>

        <section class="slide" data-dur="22000">
            <div class="inner inner--dense">
                <div class="eyebrow" data-in style="--d:0">Technologien &middot; Laravel</div>
                <h2 data-in style="--d:1">Laravel. <em>Unser Backend.</em></h2>
                <p class="lead lead--wide" data-in style="--d:2">Laravel ist das meistgenutzte Open-Source-Framework für PHP — der Baukasten, auf dem unsere Software entsteht. Seit 2011, mit riesiger Community und ausgezeichneter Dokumentation.</p>
                <div class="step" data-in style="--d:3">Was Laravel macht</div>
                <div class="grid grid--3" data-in style="--d:3">
                    <article class="card">
                        <h3>Struktur</h3>
                        <p>Model, View, Controller — Daten, Darstellung und Logik sind sauber getrennt.</p>
                    </article>
                    <article class="card">
                        <h3>Grundarbeit erledigt</h3>
                        <p>Routing, Login, Blade-Templates und Eloquent statt rohem SQL sind eingebaut.</p>
                    </article>
                    <article class="card">
                        <h3>Werkzeuge</h3>
                        <p>Artisan generiert Code und führt Migrations aus, Tests sind von Haus aus dabei.</p>
                    </article>
                </div>
                <div class="step" data-in style="--d:4">Wofür wir es einsetzen</div>
                <div class="asks" data-in style="--d:4">
                    <span class="ask">Webanwendungen</span>
                    <span class="ask">REST-APIs</span>
                    <span class="ask">Hintergrund-Jobs</span>
                    <span class="ask">Caching &amp; Sessions</span>
                </div>
                <p class="kicker" data-in style="--d:5">Laracasts, eine der grössten Lernplattformen für Entwickler, ist selbst mit Laravel gebaut.</p>
            </div>
        </section>

        <section class="slide" data-dur="22000">
            <div class="inner inner--dense">
                <div class="eyebrow" data-in style="--d:0">Technologien &middot; NativePHP</div>
                <h2 data-in style="--d:1">Mit PHP <em>aufs Handy.</em></h2>
                <p class="lead lead--wide" data-in style="--d:2">NativePHP ist eine Library auf Laravel: Dieselbe Codebase, die im Web läuft, wird zur nativen App für iOS und Android — ohne Swift, Kotlin oder ein zweites Team.</p>
                <div class="step" data-in style="--d:3">Was NativePHP ist</div>
                <div class="grid grid--3" data-in style="--d:3">
                    <article class="card">
                        <h3>Auf Laravel gebaut</h3>
                        <p>Routing, Datenbank, Blade — alles, was wir vom Web kennen, funktioniert auch in der App.</p>
                    </article>
                    <article class="card">
                        <h3>Bridges</h3>
                        <p>Kamera, Push-Nachrichten, Biometrie oder Standort werden direkt aus PHP angesprochen.</p>
                    </article>
                    <article class="card">
                        <h3>Eine Codebase</h3>
                        <p>Ein Projekt, zwei App-Stores — statt doppelter Entwicklung für iOS und Android.</p>
                    </article>
                </div>
                <div class="step" data-in style="--d:4">Wo es hilft</div>
                <div class="asks" data-in style="--d:4">
                    <span class="ask">Schneller entwickelt</span>
                    <span class="ask">Änderungen rasch umgesetzt</span>
                    <span class="ask">Bekanntes Framework</span>
                    <span class="ask">Web und App aus einem Projekt</span>
                </div>
                <p class="kicker" data-in style="--d:5">Open Source — nativephp.com</p>
            </div>
        </section>

        <section class="slide" data-dur="18000">
            <div class="inner">
                <div class="eyebrow" data-in style="--d:0">Praktikum &middot; Dein Job bei uns</div>
                <h2 data-in style="--d:1">Der ganze Weg.<br><em>Idee bis Betrieb.</em></h2>
                <p class="lead lead--wide" data-in style="--d:2">Das ist dein Job im Praktikum – von Anfang an mittendrin statt nur dabei:</p>
                <div class="grid grid--3" data-in style="--d:2">
                    <article class="card">
                        <div class="step">Zuerst</div>
                        <h3>Planen</h3>
                        <p>Herausfinden, was gebraucht wird — Requirements Engineering.</p>
                    </article>
                    <article class="card">
                        <div class="step">Dann</div>
                        <h3>Entwickeln</h3>
                        <p>Echter Code in echten Kundenprojekten, mit PHP und Laravel.</p>
                    </article>
                    <article class="card">
                        <div class="step">Und danach</div>
                        <h3>Betreiben</h3>
                        <p>Software am Laufen halten, die schon im Einsatz ist.</p>
                    </article>
                </div>
                <div class="step" data-in style="--d:4">Was dabei entsteht</div>
                <div class="results" data-in style="--d:4">
                    <span class="result">
                        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="4" width="19" height="16" rx="2"/><path d="M2.5 8.5h19"/></svg></span>
                        <b>Portale</b>
                    </span>
                    <span class="result">
                        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="12" r="2.8"/><circle cx="18.5" cy="12" r="2.8"/><path d="M8.3 12h7.4"/></svg></span>
                        <b>Schnittstellen</b>
                    </span>
                    <span class="result">
                        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 4.5 13.5H11L10 22l8.5-11.5H12L13 2Z"/></svg></span>
                        <b>Automatisierungen</b>
                    </span>
                    <span class="result">
                        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg></span>
                        <b>Dashboards</b>
                    </span>
                </div>
            </div>
        </section>

        @if($mentors->isNotEmpty())
            <section class="slide" data-dur="16000">
                <div class="inner">
                    <div class="eyebrow" data-in style="--d:0">Fragen zum Praktikum</div>
                    <h2 data-in style="--d:1">Frag die beiden.<br><em>Das ist ihr Alltag.</em></h2>
                    <div class="people" data-in style="--d:2">
                        @foreach($mentors as $mentor)
                            <article class="person">
                                <div class="portrait">
                                    <img src="{{ \App\Support\CloudinaryUrl::src($mentor->image, 256) }}" alt="{{ $mentor->name }}" width="240" height="240">
                                </div>
                                <div class="person-copy">
                                    <h3>{{ $mentor->name }}</h3>
                                    @if($mentor->role)
                                        <p class="role">{{ $mentor->role }}</p>
                                    @endif
                                    @if(is_string($mentor->icons['email'] ?? null))
                                        <p class="mail">{{ $mentor->icons['email'] }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="slide" data-dur="18000">
            <div class="inner">
                <div class="split">
                    <div class="col">
                        <div class="eyebrow" data-in style="--d:0">Bewerbung</div>
                        <h2 data-in style="--d:1">Praktikum <em>2027/28.</em></h2>
                        <p class="lead lead--sm" data-in style="--d:2">Wir starten bei deinem bestehenden Wissensstand. Mitbringen solltest du:</p>
                        <div class="asks" data-in style="--d:3">
                            <span class="ask">Eigeninitiative</span>
                            <span class="ask">Selbstständiges Lernen &amp; Arbeiten</span>
                            <span class="ask">Lust, Software zu konzipieren &amp; entwickeln</span>
                        </div>
                    </div>
                    <div class="qr" data-in style="--d:2">
                        <img src="{{ localized_route('jobs.internship.qr.image') }}" alt="QR-Code zu {{ Str::after(localized_route('jobs.internship.show'), '://') }}" width="580" height="580">
                        <span>{{ Str::after(localized_route('jobs.internship.show'), '://') }}</span>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="chrome chrome--bottom">
        <div class="bar" aria-hidden="true"><i class="fill" id="fill"></i><span class="ticks" id="ticks"></span></div>
    </div>

    <div class="tap">
        <button type="button" id="prev" aria-label="Vorherige Folie"></button>
        <button type="button" id="next" aria-label="Nächste Folie"></button>
    </div>
</div>

</body>
</html>
