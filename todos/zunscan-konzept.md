# Zunscan — Refactor-Konzept

Stand: 2026-08-05 · Betrifft `zunscan.codebar.ch` (Sub-Site in diesem Repo, eigener Design-Namespace)

## Ausgangslage

Die Zunscan-Seite ist migriert, zweisprachig (`/de-ch`, `/en-ch`) und läuft. Was jetzt ansteht, sind
fünf Punkte, die inhaltlich und gestalterisch offen geblieben sind:

1. Die Navigation heisst noch «Dienstleistungen», obwohl seit dem Depublizieren von ePost nur noch
   **eine** Dienstleistung existiert.
2. Die Kontaktseite nennt ausschliesslich die paperflakes AG. Tatsächlich ist die paperflakes AG ein
   **Joint Venture** der codebar Solutions AG und der Real Estate Club GmbH — das steht nirgends.
3. Der Footer hatte drei Linkspalten, von denen eine seit dem ePost-Entfernen nur noch einen Eintrag
   trägt. Er wirkt leer.
4. Das Design hat **keine Tokens**: kein Type-Scale, kein Spacing-Rhythmus, keine Radius-/Shadow-Werte.
   Jede Seite setzt `text-4xl`, `py-24`, `py-16`, `rounded` von Hand. Das ist der eigentliche Grund,
   weshalb es «nicht frisch» wirkt — nicht die Farben.
5. SEO ist unvollständig: **kein `<link rel="canonical">`**, kein `x-default`, kein JSON-LD.

Der Header/Navigationsbereich bleibt in allen Punkten unverändert — der ist gesetzt.

---

## 1. Navigation: «Dienstleistungen» → «Digitalisierung»

Der Header-Eintrag nutzt heute `zunscan.nav.services` und verlinkt bereits auf
`services.scanning.show`. Er wird auf den bestehenden Key `zunscan.nav.scanning` umgestellt
(«Digitalisierung» / «Digitization»). Der Key `zunscan.nav.services` wird danach nirgends mehr
verwendet und entfällt aus beiden Sprachdateien.

Betrifft: `resources/views/components/zunscan/patials/header.blade.php` (Desktop + Mobile-Drawer),
`lang/de_CH/zunscan.php`, `lang/en_CH/zunscan.php`.

**Nebenbefund — toter Code kostet CSS.** `resources/views/zunscan/services/show/epost.blade.php` und
`ServicesEpostController` sind zwar nicht mehr geroutet, liegen aber weiterhin unter
`@source '../views/zunscan'`. Tailwind scannt sie also und zieht ihre Klassen weiter ins
Produktions-Bundle. Entweder löschen (Git hat sie) oder aus dem `@source`-Pfad herausschieben.
Empfehlung: löschen, inklusive der `zunscan.services.epost.*`-Keys.

---

## 2. Kontaktseite — Joint Venture mit zwei Ansprechpersonen

Die heutige Seite (zwei identische paperflakes-Adressblöcke) wird vollständig ersetzt.

**Neuer Aufbau:**

1. **Titel** — «Kontakt» / «Gerne für Sie da, digital und analog» (bleibt).
2. **Joint-Venture-Absatz** (neuer Text, DE + EN): zunscan.ch wird von der paperflakes AG betrieben,
   einem Gemeinschaftsunternehmen der codebar Solutions AG und der Real Estate Club GmbH. Zwei
   Firmen, zwei Ansprechpersonen — das ist die Aussage, nicht «wir sind gross».
3. **Zwei Personenkarten** nebeneinander (`sm:grid-cols-2`, mobil gestapelt):

   | | Sebastian Bürgin-Fix | Mischa Lanz |
   |---|---|---|
   | Rolle | codebar Solutions AG | Real Estate Club GmbH |
   | E-Mail | `sebastian.buergin@codebar.ch` | `mischa.lanz@realestateclub.ch` |
   | Telefon | `+41 61 515 60 95` | `+41 61 515 20 40` |
   | Website | `www.codebar.ch` | `www.realestateclub.ch` |
   | LinkedIn | `/in/sebastian-buergin/` | `/in/mischa-lanz-672a65112/` |
   | Porträt | Cloudinary (vorhanden) | Cloudinary (vorhanden) |

   Beide Karten tragen damit denselben Satz Kanäle — E-Mail, Telefon, Website, LinkedIn — und bleiben
   symmetrisch.

4. **Zwei Firmen-Adresskarten**: Real Estate Club GmbH, Hauptstrasse 91, CH-4455 Zunzgen ·
   codebar Solutions AG, Langegasse 39, CH-4104 Oberwil.

**Porträts.** Beide existieren bereits als Cloudinary-Assets und müssen nicht neu beschafft werden:

- Sebastian: `…/www-codebar-ch/team/Sebastian_V3.webp` (aus `database/files/team/sebastian-buergin-fix.yaml`, aktuell gepflegt)
- Mischa: `…/www-paperflakes-ch/people/6528f7c6bddf8430fb5d154c_Mischa_Hemd.webp` (nur noch in der
  Git-Historie, aus dem gelöschten `database/seeders/Paperflakes/ContactsTableSeeder.php`)

Die CSP erlaubt `res.cloudinary.com` bereits — Hotlinking von realestateclub.ch wäre dagegen
blockiert. Für die Bildtransformation wird `App\Support\CloudinaryUrl::src()/srcset()` wiederverwendet;
das ist eine reine PHP-Hilfsklasse ohne Design-Kopplung, verschmutzt den Zunscan-Namespace also nicht.

**Datenhaltung.** Keine Models, keine Datenbank — gemäss der bestehenden Regel für diese Sub-Site.
Namen, Adressen, Mail, Telefon und Bild-URLs sind sprachunabhängig und wandern als Array nach
`config/zunscan.php` (dort liegt bereits `domain`). Übersetzt wird nur der Fliesstext in den
`lang/*/zunscan.php`. So bleibt das Markup frei von Daten und die Personenliste ohne Blade-Eingriff
pflegbar.

**Neue Komponente** `x-zunscan.components.person` — Porträt, Name, Firma, Mail/Telefon/Website.
Im Zunscan-Namespace existiert noch keine Personen-Komponente; die Karten des Hauptauftritts
(`x-card.person-card`) sind bewusst **nicht** wiederverwendbar, weil sie an dessen Design-Tokens hängen.

---

## 3. Footer neu aufteilen

Drei Linkspalten für vier Links funktionieren nicht mehr. Neu:

```
┌─────────────────────┬──────────────────┬──────────────────┐
│ paperflakes AG      │ Navigation       │ Rechtliches      │
│ Mühlematten 12      │ Start            │ Medien           │
│ CH-4455 Zunzgen     │ Digitalisierung  │ Impressum        │
│ info@paperflakes.ch │ Über uns         │ Datenschutz      │
│ +41 …               │ Kontakt          │                  │
├─────────────────────┴──────────────────┴──────────────────┤
│ © 2026 Zunscan. Ein Projekt der paperflakes AG.   [ sds ] │
└───────────────────────────────────────────────────────────┘
```

- Spalte 1 wird zum **Kontaktblock** — füllt den leeren Raum mit etwas Nützlichem statt mit Luft.
- **LinkedIn entfällt.** Das Copyright rückt an dessen Stelle (Bottom-Bar links).
- Das **swiss-digital-services-Logo** rutscht in dieselbe Bottom-Bar nach rechts; die beiden
  gestapelten Halbzeilen von heute werden zu einer Zeile.

**Zum Logo:** eine weisse Variante existiert weder im Repo noch auf Cloudinary — alle Fundstellen sind
explizit `-black`. Zwei Wege: (a) `filter: brightness(0) invert(1)` als `@utility` in `zunscan.css`,
(b) offizielles weisses Asset von swissmadesoftware.org beziehen. Vorsicht bei (a): die Grafik hat
*weisse* Aussparungen (Plus und Zahnrad im Ordner-Icon). Sind die als weisse Pixel und nicht als
Transparenz angelegt, werden sie beim Invertieren ebenfalls weiss und die Binnenzeichnung geht
verloren. **Vor dem Umsetzen visuell prüfen**; Fallback ist ein weisser Chip hinter dem Logo statt
einer Invertierung.

---

## 4. Design-Refresh — «Paper Canvas + Cards»

Farben und Schrift bleiben exakt wie sie sind. Was sich ändert, ist Struktur und Konsistenz.

### Das eigentliche Problem

Heute wechseln fünf vollflächige Farbbänder pro Seite: paper → Blau-Verlauf → paper →
**Dunkelgrau** → Blau-Footer. Drei Farbfamilien, und das Dunkelgrau der CTA taucht sonst nirgends als
Fläche auf. Dazu kommt: jede Seite bestimmt ihre Abstände und Schriftgrössen selbst.

### Die Regel

- **Paper ist die Fläche.** Inhaltsseiten stehen auf Papiertextur.
- **Weisse Karten tragen den Inhalt** — ein Radius, ein Schatten, überall derselbe.
- **Blau erscheint maximal zweimal pro Seite**: das CTA-Band und der Footer.
- **Dunkelgrau wird als Fläche pensioniert** und bleibt reine Textfarbe.

### Tokens (neu in `resources/css/zunscan.css`)

Analog zur Disziplin von `app.css`, aber mit Zunscans eigenen Werten:

```css
@theme {
    /* Type-Scale, fluid 360 → 768 — ersetzt text-4xl/sm:text-6xl von Hand */
    --text-display:  clamp(2rem, 1.5rem + 2.2vw, 3.5rem);
    --text-title:    clamp(1.5rem, 1.2rem + 1.5vw, 2.25rem);
    --text-heading:  clamp(1.25rem, 1.1rem + 0.8vw, 1.5rem);
    --text-lead:     clamp(1.125rem, 1.05rem + 0.4vw, 1.375rem);
    --text-eyebrow:  0.8125rem;   /* uppercase, 700, tracking .12em */

    /* ein einziger vertikaler Rhythmus */
    --spacing-section: clamp(3rem, 2rem + 5vw, 6rem);

    /* Touch-Target nach WCAG 2.5.8 */
    --spacing-control: 2.75rem;

    --radius-card: 0.75rem;
    --shadow-card: 0 12px 30px -18px rgb(22 57 90 / 0.35);
}
```

### Neue Komponenten (Zunscan-Namespace)

| Komponente | Zweck |
|---|---|
| `components/section` | vertikaler Rhythmus, ersetzt `py-24`/`py-16` von Hand |
| `components/card` | weisse Karte: Radius + Schatten, einmal definiert |
| `components/eyebrow` | kleine Versal-Zeile über Titeln |
| `components/person` | Personenkarte für die Kontaktseite (siehe 2.) |
| `patials/cta-band` | das *eine* blaue Band, ersetzt `contactcta` samt Grau-Verlauf |

### Seitenwirkung

- **Start**: die vier Nutzenversprechen werden zu vier weissen Karten auf Papier, je mit einem
  Inline-SVG-Icon (Stil wie die bereits vorhandenen Icons, keine neue Abhängigkeit). Das blaue
  Vollband entfällt.
- **Digitalisierung**: die Preisboxen sind bereits Karten — sie erben Radius/Schatten und verlieren
  das blaue Vollband darunter.
- **Über uns / Medien / Impressum / Datenschutz**: auf Papier, Text in einer Karte.
- **CTA**: das eine blaue Band, direkt über dem Footer.

### Mobile

- Karten stapeln einspaltig; die Clamps regeln die Schriftgrössen ohne `sm:`-Varianten.
- Tap-Targets in Navigation und Sprachumschalter auf 44 px (heute 36 px).
- Die bereits verifizierte Overflow-Freiheit (`scrollWidth === innerWidth`) wird nach dem Umbau
  erneut geprüft.

---

## 5. SEO nachziehen

| Punkt | Status heute | Massnahme |
|---|---|---|
| `<link rel="canonical">` | **fehlt komplett** | pro Seite ergänzen, aus dem Request-Host gebaut |
| `hreflang` DE/EN | vorhanden | bleibt |
| `hreflang="x-default"` | fehlt | auf `de-ch` zeigen (wie im Hauptauftritt) |
| JSON-LD | keins | minimaler Graph: `Organization` + `WebSite`; auf der Kontaktseite zusätzlich `ContactPage` mit beiden Firmen |
| `sitemap.xml` | dynamisch, beide Sprachen, ohne ePost | bleibt; neue/entfallende Seiten dort nachführen |
| `robots.txt` | pro Domain, korrekt | bleibt |
| Title-Muster | nackt («Kontakt») | **Entscheid offen**: Suffix «| zunscan.ch» ergänzen? Der Hauptauftritt verzichtet bewusst darauf — bei einer eigenen Marke spricht aber einiges dafür. |

Wichtig bei allen absoluten URLs: `AppServiceProvider` erzwingt global
`URL::forceRootUrl(config('app.url'))`. Die Zunscan-Middleware korrigiert das bereits pro Request auf
den echten Host — Canonical und JSON-LD müssen sich darauf stützen und dürfen `config('app.url')`
nicht direkt lesen.

---

## Offene Punkte — alle entschieden, umgesetzt

1. **Mischas Kontaktdaten** liegen vor (`mischa.lanz@realestateclub.ch`, `+41 61 515 20 40`).
   Die veraltete `@paperflakes.ch`-Adresse aus der Git-Historie wurde **nicht** übernommen.
2. **paperflakes AG**: nur im Einleitungsabsatz als Betreiberin genannt, Adresse im Footer und im
   Impressum — keine dritte Karte.
3. **Title-Suffix**: umgesetzt, `<title>` ist jetzt «Seitentitel | zunscan.ch». `og:title` bleibt
   bewusst nackt, damit geteilte Links nicht doppelt branden.
4. **Logo-Invertierung: verworfen.** Die im Konzept beschriebene Befürchtung hat sich bestätigt —
   `brightness(0) invert(1)` flacht Plus und Zahnrad im Ordner-Icon zur weissen Silhouette ab, die
   Binnenzeichnung geht verloren. Umgesetzt ist der Fallback: das schwarze Original auf einem
   weissen Chip (`rounded-card`, passend zur Kartensprache des Refreshs). Die `logo-invert`-Utility
   wurde wieder entfernt.

## Nachtrag: was während der Umsetzung zusätzlich auffiel

- **Post-Scanning-Kachel deaktiviert.** Sie bewarb ePost, das keine Seite mehr hat. Auskommentiert
  statt gelöscht, samt Verweis auf die weiterhin gepflegten `zunscan.start.mail_*`-Texte — kommt
  mit ePost zurück.
- **Kein Favicon.** Jede Zunscan-Seite hat bisher einen 404 auf `/favicon.ico` produziert. Das
  Logo liegt als SVG vor und dient jetzt als Icon.
- **Alpine-CSP-Build verifiziert.** Der Wechsel auf ein eigenes `resources/js/zunscan.js` (nötig,
  weil `app.js` das `app.css` des Hauptauftritts importiert und dessen Tokens mitgeliefert hätte)
  stand unter dem Verdacht, `x-data="{ mobileMenu: false }"` zu brechen. Per CDP geprüft: das
  Mobile-Menü schaltet korrekt von `display:none` auf `block`, der Parser dieses CSP-Builds kommt
  mit dem Inline-Objekt zurecht.
- **Porträt-Zuschnitt.** Beide Bilder laden, sind aber unterschiedlich gerahmt: Sebastians
  Cloudinary-URL trägt `e_background_removal` + `c_thumb,g_face` (wird von `CloudinaryUrl` nicht
  gestrippt, weil das erste Segment nicht wie eine Transformation aussieht), Mischas wird auf
  `c_fill` normalisiert. Funktioniert, wirkt aber nicht ganz gleich. Sauberste Lösung wären zwei
  gleich zugeschnittene Quellbilder — bewusst offengelassen, weil das eine Asset-Frage ist und
  keine Code-Frage.

## Reihenfolge

1. Tokens + `section`/`card`/`eyebrow` — das Fundament, ohne das jeder weitere Schritt wieder
   Handarbeit wäre
2. Navigation umbenennen, ePost-Leichen entfernen
3. Seiten umstellen: Start → Digitalisierung → Über uns → Medien → Legal
4. Kontaktseite neu (braucht Punkt 1 aus «Offene Punkte»)
5. Footer
6. SEO: Canonical, x-default, JSON-LD
7. Tests + visuelle Abnahme Desktop/Mobile

## Verifikation

- `./vendor/bin/pest`, `./vendor/bin/phpstan analyse`, `./vendor/bin/pint` grün
- Bestehende Zunscan-Tests erweitern: Canonical vorhanden und auf dem richtigen Host, `x-default`
  gesetzt, JSON-LD parst, Kontaktseite zeigt beide Personen, Navigation enthält «Dienstleistungen»
  nicht mehr
- Regression, die schon einmal zugeschlagen hat: `public/build/assets/app-*.css` enthält **keine**
  `zunscan-*`-Klassen und umgekehrt
- Visuelle Abnahme über CDP mit echter Viewport-Emulation (`Emulation.setDeviceMetricsOverride`,
  390 px und 1440 px) — `--screenshot` mit `--window-size` allein emuliert das Viewport nicht korrekt
  und hat hier bereits einen Fehlalarm produziert

---

## Feedback-Runde 2 (umgesetzt)

- **Footer**: Kontaktspalte entfernt, zwei Spalten (Navigation, Rechtliches). Die Zeile unten nennt
  jetzt beide Eigentümerinnen verlinkt — «Ein Projekt der Real Estate Club GmbH & codebar Solutions
  AG». Auf dem Handy stapeln Zeile und sds-Logo zentriert.
- **Start-Kacheln**: zwei Zeilen für den Titel reserviert (`sm:min-h-[2lh]`), damit der Fliesstext in
  allen drei Karten auf derselben Grundlinie beginnt — «Platzersparnis» ist einzeilig, die anderen
  beiden brechen um. Auf «Über uns» bewusst *nicht*, dort sind alle Titel einzeilig und die
  Reservierung wäre nur eine Lücke.
- **Trennung Header/Inhalt**: Hero und erste Sektion liegen beide auf Papier und liefen ineinander.
  Eine Haarlinie unter dem Hero trennt sie jetzt.
- **«Was wir tun» neu getextet**: statt zwei Absätzen Marketingprosa drei Karten — Was wir tun /
  Wer dahintersteht / Wie wir arbeiten. Konkret (Handaufbereitung, OCR, Revisionssicherheit,
  zertifizierte Vernichtung) und mit dem Joint Venture als Teil der Geschichte.
- **Kontakt**: Mischa Lanz zuerst, Website als Unterzeile, Mail/Telefon/LinkedIn als Icons mit
  Screenreader-Namen. codebar hat eine zweite Adresse (Hauptstrasse 91) — `locations` trägt deshalb
  pro Firma eine Adressliste.
- **Impressum**: Adresse korrigiert auf **Hauptstrasse 91** (war Mühlematten 12) und aus dem Markdown
  in einen strukturierten Block aus `config('zunscan.company')` verschoben. Grund: CommonMark macht
  aus einfachen Zeilenumbrüchen Leerzeichen, die Adresse stand deshalb als eine Zeile da — und sie
  war an drei Orten gepflegt (Markdown, Footer, JSON-LD), was die falsche Adresse überhaupt erst
  überleben liess. Jetzt eine Quelle, plus ein Test, der «Mühlematten» auf allen Seiten verbietet.
- **Legal-Layout**: eigene `.legal-prose`-Regeln statt des Typography-Plugins, Lesebreite auf
  `max-w-3xl` begrenzt. Das Plugin ist damit raus — das Zunscan-CSS ist von 36.1 kB auf 24.6 kB
  gefallen.
