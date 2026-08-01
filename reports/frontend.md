# Frontend-Audit — Phase 1

**Stand:** 2026-08-01, Branch `feature-updates` (Working Tree, nicht Commit — die Dateien wurden während des Audits aktiv bearbeitet)
**Scope:** `resources/views/`, `resources/css/app.css`, `resources/js/app.js`
**Es wurde kein Code geschrieben.**

> **Umgesetzt am 2026-08-01** — siehe [Kapitel 10](#10-umsetzung). Alle Befunde sind abgearbeitet
> bis auf 3.8 (person-card / network-user-card), das bewusst offen bleibt. Bundle 119 543 → 81 179 B
> (gzip 19 635 → 13 959 B), Dark-Mode-Regeln 14 → 0, 390 Tests grün.

---

## 0. Vorbemerkung — was dieses Audit *nicht* gefunden hat

Der Prompt geht von einem Frontend aus, das noch kein Designsystem hat. Das trifft hier nicht zu, und das ändert die Prioritäten deutlich. Verifiziert:

| Erwartete Baustelle | Realität |
|---|---|
| Kein/mehrere Layout-Shells | **Ein** Shell (`layouts/app.blade.php`), 27 von 27 Seiten nutzen ihn |
| `tailwind.config.js` vs. CSS-first | CSS-first, `@theme` mit 30 Tokens, keine JS-Config |
| Magic Numbers / arbitrary values | **11** im Produktivcode — alle begründet (`aspect-[16/9]`, `grid-cols-[12rem_1fr]`, …) |
| Button/Input/Card ad-hoc | Existieren als Komponenten mit Lookup-Arrays, exakt im geforderten Stil |
| Fehlender Viewport-Tag | Vorhanden, `layouts/app.blade.php:8` |
| Fehlendes `cursor-pointer` (v4-Preflight) | Global restauriert, `app.css:121-131` — inkl. Checkbox/Radio/Select/File |
| Uneinheitliche Control-Höhen | `--spacing-control: 2.75rem` (44 px), von Input, Button, File, Badge-Link geteilt |
| Fehlende Breadcrumbs/`aria-current` | `x-breadcrumbs` mit `<nav aria-label>`, `<ol>`, `aria-current="page"` |
| Skip-Link | Vorhanden, `layouts/app.blade.php:39` |

**Die verbleibenden Probleme sind anderer Natur:** zwei Stellen, an denen fremder bzw. toter Code die eigenen Regeln bricht (Kapitel 2), und eine Handvoll Muster, die dem Rule-of-three-Kriterium genügen, aber noch nicht extrahiert sind (Kapitel 4). Das ist eine deutlich kleinere Baustelle als der Prompt vorsieht — und ich empfehle ausdrücklich, sie auch klein zu halten statt ein zweites Systemlayer darüberzulegen.

---

## 1. Inventar

### Stack (verifiziert, nicht angenommen)

| | |
|---|---|
| Laravel | **13.23.0** (`php artisan --version`) |
| Tailwind | **4.1.2**, CSS-first, kein `tailwind.config.js` |
| CSS-Entry | **`resources/css/app.css`** — 866 Zeilen, die einzige Stylesheet-Datei |
| Plugins | `@tailwindcss/typography`, `@tailwindcss/forms`, `@tailwindcss/vite` |
| JS | **Alpine.js 3.14 CSP-Build** + `@alpinejs/focus` — bereits installiert, 9 registrierte `Alpine.data()`-Komponenten |
| Dark Mode | Im eigenen Code **nirgends** — aber siehe Befund 2.1 |

### Blade-Bestand — 174 Dateien

| Verzeichnis | Dateien | Zeilen | Rolle |
|---|---:|---:|---|
| `app/` | 26 | ~1 200 | Seiten |
| `components/` | 70 | ~1 900 | Komponentenbibliothek |
| `layouts/` | 12 | ~800 | Shell, Nav, Footer, SEO |
| `errors/` | 13 | ~120 | HTTP-Fehlerseiten |
| `markdown/` | 7 | ~150 | News-Blöcke (Callout, Figure, Steps, …) |
| **`demo/`** | **46** | **3 196** | **Prototypen — 44 % aller Blade-Zeilen** |

Komponenten nach Gruppe: `ui` 10 · `icon` 10 · `card` 9 · `news` 8 · `layout` 6 · `form` 5 · `ai-llm` 4 · `nav` 2 · `data` 2 · Rest einzeln.

### Token-Setup (`app.css`, `@theme`)

Farbe: `--color-brand #500472`, `--color-brand-strong #3a0354`, plus semantische Aliasse `surface`, `border`, `border-soft`, `border-strong`, `muted`, `hint`.
Typo: fluide `clamp()`-Skala — `display`, `title`, `heading`, `subheading`, `lead`, `eyebrow`. Kein `md:`-Step nötig.
Raum: `--spacing-section 2rem`, `--spacing-control 2.75rem`, `--spacing-control-sm 2.25rem`.
Form: `--radius-pill 0.375rem`, `--radius-panel 0.75rem`, `--shadow-pop` (der einzige Schatten).
Breite: `--container-frame 60rem`, `--container-reading` daraus abgeleitet.

### Kontrast — nachgemessen (sRGB, WCAG 2.x)

| Token | auf Weiss | auf `surface` | Verdikt |
|---|---:|---:|---|
| `brand` #500472 | **13.11** | 12.55 | AAA |
| Weiss auf `brand` | **13.11** | — | AAA — beide Richtungen tragen |
| `brand-strong` #3a0354 | 15.90 | 15.21 | AAA |
| `gray-900` (Headings) | 17.75 | 16.98 | AAA |
| `gray-800` (Body) | 14.67 | 14.04 | AAA |
| `muted` = gray-600 | 7.56 | 7.23 | AAA |
| `hint`/`border-strong` = gray-500 | 4.84 | **4.63** | AA (knapp) |
| `red-600` (Fehler) | 4.77 | **4.56** | AA (knapp) |
| `emerald-700` | 4.89 | 4.68 | AA |
| `gray-400` | 2.60 | 2.49 | **nur dekorativ** |
| `gray-300` (Trenner) | 1.47 | 1.41 | **nur dekorativ** |

Die Palette ist sauber. Zwei Anmerkungen in Kapitel 7.

---

## 2. Befunde mit Produktionswirkung

### 2.1 Dark Mode wird ausgeliefert — über Laravels Default-Pagination

Der Guardrail „kein Dark Mode" ist im eigenen Code eingehalten und im gebauten CSS trotzdem verletzt.

`app/ai/llm/analytics.blade.php:118` ruft `$periods->links()` auf. Es gibt kein `resources/views/vendor/pagination/`, kein `Paginator::defaultView()` in einem Provider — also greift Laravels `pagination::tailwind`. Und `app.css:95` zieht genau diese Vendor-View bewusst in den Build:

```css
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
```

Nachgewiesen im Artefakt `public/build/assets/app-R-Aa4p43.css` (119 543 B / 19 635 B gzip):

```
@media (prefers-color-scheme:dark){ .dark\:border-gray-600{…} .dark\:bg-gray-700{…} … }
```

14 `dark:*`-Regeln, darunter `dark:focus:border-blue-700`, `dark:active:bg-gray-700`, `dark:text-gray-400`.

Die Vendor-View bricht zusätzlich vier Systemregeln auf einmal:

| Vendor-View | System |
|---|---|
| `rounded-md` | `rounded-pill` / `rounded-panel` |
| `border-gray-300` | `border-border` |
| `focus:ring ring-gray-300 focus:border-blue-300` | `focus-visible:outline-brand` |
| `px-4 py-2` ≈ 36 px | `min-h-control` = 44 px |

Das ist die einzige Stelle im Projekt, an der eine Bedienoberfläche unter dem Touch-Target liegt, den der Rest der Site durchsetzt — und sie ist öffentlich erreichbar.

**Fix:** eigene Pagination als `x-ui.pagination` (oder publizierte `vendor/pagination/tailwind.blade.php`), danach die `@source`-Zeile ersatzlos streichen.

### 2.2 Die Demo-Prototypen landen im Produktions-CSS

Die Routen sind sauber gegated (`routes/web.php:130` — `if (! app()->isProduction())`). Das CSS ist es nicht: `app.css:94` sourcet `'../views'` pauschal, also auch `resources/views/demo/`.

Was dadurch im ausgelieferten Stylesheet steht:

- **142** arbitrary values aus `demo/` → **53** distinkte Regeln im Bundle (Produktivcode: 11)
- Hartcodierte Marken-Hex ausserhalb des Tokensystems: `text-[#500472]` 17×, `bg-[#500472]` 12×, `border-[#500472]` 4×, `bg-[#3a0354]` 3×, `text-[#c026d3]` 3×
- 23× `leading-[1.1]`, dazu `leading-[1.05]`, `[1.08]`, `[1.12]`, `[0.95]` — fünf Werte für eine Aufgabe
- Eigene Type-Ramps: 10× `text-[clamp(2rem,1.4rem+2.6vw,3rem)]` und sieben weitere Clamps parallel zur `@theme`-Skala
- `@keyframes demo-marquee`, `.demo-marquee`, `.demo-dotgrid` in `app.css:795-811` — im Bundle verifiziert

Zusätzlich sind die 46 Demo-Dateien beim Grepping ein permanenter Störfaktor: jede Suche nach „wo wird Marke X gesetzt" liefert zuerst Prototypen.

**Fix:** `@source` auf die echten Verzeichnisse einschränken (bzw. `@source not '../views/demo'`), die zwei Demo-Blöcke aus `app.css` heraus, und für den Ordner ein Verfallsdatum setzen. Der Kommentar in `app.css:787-794` sagt bereits „Delete both blocks together with `resources/views/demo/` once a direction is picked" — die Entscheidung steht offenbar aus.

### 2.3 Zwei Tabellen, zwei Konventionen, keine Komponente

| | `app/ai/llm/analytics.blade.php:96` | `components/ai-llm/archive-table.blade.php` |
|---|---|---|
| Scroll-Container | `<div class="overflow-x-auto">` **im** Panel | `overflow-x-auto` **auf** dem Panel |
| Zeilentrenner | `border-b border-border-soft` | `border-t border-border-soft` |
| Kopftrenner | `border-b border-border` | keiner |
| Kopffarbe | `text-gray-500` | `text-gray-500` |
| Zellpadding | `py-2 pr-4` (15×) | `py-2 pr-4` (2×) |
| Mobile | `hidden sm:table-cell` auf 2 Spalten | keine Reduktion |

Beide Tabellen scrollen korrekt und verursachen kein horizontales Page-Scrolling. Aber die Konventionen sind gespiegelt, und `py-2 pr-4` steht 17×. Das ist der klarste Rule-of-three-Fall im Projekt.

---

## 3. Inkonsistenzen (ohne Produktionswirkung)

| # | Befund | Ort | Warum es zählt |
|---|---|---|---|
| 3.1 | **Zwei Focus-Ring-Mechanismen.** `x-ui.button` nutzt `focus-visible:ring-2 ring-offset-2 ring-brand`, alle 22 anderen Stellen `focus-visible:outline-2 outline-offset-2 outline-brand`. | `components/ui/button.blade.php:13` vs. Rest | `ring` malt einen Box-Shadow, `outline` einen echten Outline — sie sehen bei `border-radius` unterschiedlich aus und stapeln anders |
| 3.2 | **`text-gray-500` statt `text-muted`.** 6 Vorkommen, wo `--color-muted` (gray-600) das Token wäre. | `stat-card:11,15`, `analytics:98`, `archive-table:10`, `opening-hours:69`, `_footer:51` | 4.84:1 statt 7.56:1 — beides AA, aber zwei Grautöne für dieselbe Rolle |
| 3.3 | **Type ausserhalb der Skala.** `text-2xl` (stat-card Wert), `text-3xl` (Co-Working Preis) | `card/stat-card.blade.php:5`, `app/co-working/index.blade.php:35` | Die beiden grössten Zahlen der Site sind die einzigen, die keinem Token folgen — sie skalieren auf dem Phone nicht mit |
| 3.4 | **Kontaktblock zweimal, in umgekehrter Reihenfolge.** Kontakt: Telefon → E-Mail. Impressum: E-Mail → Telefon. Identisches Markup. | `app/contact/index.blade.php:5-16`, `app/legal/imprint/index.blade.php:12-22` | Gleiche Information, zwei Anordnungen |
| 3.5 | **Anzeigetext von Telefon/E-Mail hartcodiert**, während der `href` aus `config('company.*')` kommt. 5 Vorkommen. Ein Fall komplett hartcodiert: `mailto:info@codebar.ch`. | s. 3.4 + `app/jobs/index.blade.php:30` | Nummernwechsel in `config/company.php` ändert den Link, nicht die Beschriftung |
| 3.6 | **„Logo-Wash"-Gradient 3× ausgeschrieben** (`from-fuchsia-600/10 via-brand/10 to-blue-600/10`) | `card/nav-card.blade.php:5`, `opening-hours.blade.php:50`, `news/table-of-contents.blade.php:10` | Der Wash ist ein Markenelement ohne Token |
| 3.7 | **Seiten-Notiz `<p class="mb-2 text-muted">` 3×** — steht ausserhalb jeder `x-layout.section` und damit ausserhalb des `mt-section`-Rhythmus | `legal/privacy:4`, `legal/terms:4`, `errors/_error-page:5` | Klebt am Page-Header; einziger Ort, an dem der Rhythmus umgangen wird |
| 3.8 | **`x-card.person-card` und `x-card.network-user-card`** rendern beide Avatar + Name + Rolle + `x-ui.social-links`, in zwei unterschiedlichen Grössenwelten (`size-32`/`text-base` vs. `size-8`/`text-base`, `rounded-panel` vs. `rounded-full`) | `components/card/` | Dieselbe Person sieht je nach Seite anders aus |
| 3.9 | **Tote Varianten.** `x-ui.alert` `success`/`error` nie aufgerufen; `x-ui.badge` `solid` nie; `x-ui.row` `compact` nie | `components/ui/` | Ungetestete Pfade |

---

## 4. Duplication Map — Vertrag für den Refactor

Alles hier erfüllt Rule-of-three. Jede Zeile muss am Ende eine einzige Quelle haben.

| Muster | Anzahl | Dateien | Single Source of Truth |
|---|---:|---:|---|
| `focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand` — **11 Schreibvarianten** (offset-2 / -offset-2 / offset-4 / ohne offset / outline-white) | **23** | 16 | `@utility focus-ring` + `focus-ring-inset` in `app.css` |
| `min-h-control … sm:min-h-0` (Touch-Target-Ladder) | **15** | 12 | `@utility tap-target` |
| Tabellen-Zellpadding `py-2 pr-4` | **17** | 2 | `x-ui.table` / `x-ui.table.cell` |
| `grid size-control place-items-center rounded-pill` (Icon-Button) | 4 | 4 | `x-ui.icon-button` |
| Gradient „Logo-Wash" | 3 | 3 | `--gradient-wash` Token oder `@utility bg-wash` |
| `text-gray-500` statt `text-muted` | 6 | 5 | `text-muted` |
| Seiten-Notiz `mb-2 text-muted` unter dem Header | 3 | 3 | `<x-slot:note>` an `x-layout.page-header` |
| Telefon/E-Mail-Anzeigetext | 5 | 3 | `config('company.*')` + `x-ui.contact-line` |
| Kontaktblock Telefon+E-Mail | 2 | 2 | `x-ui.contact-pair` (Grenzfall — 2×, aber identisch bis auf die Reihenfolge) |
| `x-ui.badge`-Aufruf-Wrapper `flex flex-wrap gap-2` | 4 | 4 | schon da: `x-data.tag-list` konsequent nutzen |

Nicht in der Map, bewusst: `x-illustration-row` (`xl:pr-14`/`xl:pl-18`), `x-ui.row`-Grid-Templates, `aspect-[16/9]`/`aspect-[3/1]`. Alle dokumentiert begründet, alle in genau einer Datei.

---

## 5. Komponenten-Kandidaten

| Neu | Ersetzt | Props | Priorität |
|---|---|---|---|
| `x-ui.pagination` | Laravels `pagination::tailwind` | `:paginator` | **hoch** (Befund 2.1) |
| `x-ui.table` (+ `.head`, `.row`, `.cell`) | 2 handgebaute Tabellen | `align`, `hideBelow`, `numeric` | **hoch** (Befund 2.3) |
| `x-ui.icon-button` | 4 Icon-Buttons in Nav/Combobox/Social | `label` (a11y), `size` | mittel |
| `x-ui.contact-line` | 5 Telefon-/Mail-Links | `channel` (`phone`\|`email`) | mittel |
| `x-ui.person` (Konsolidierung) | `person-card` + `network-user-card` | `size` (`sm`\|`lg`), `user` | mittel |
| `x-slot:note` an `page-header` | 3 lose `<p>` | — | niedrig |

**Nicht anlegen** — der Prompt schlägt sie vor, sie existieren aber schon oder wären Overhead: `x-layouts.app` (= `x-app-layout`), `x-button`/`x-input`/`x-badge`/`x-alert`/`x-card`/`x-nav`/`x-nav.link`/`x-breadcrumbs`/`x-form.field` (alle vorhanden), `x-layouts.guest` (es gibt keinen Auth-Bereich), `x-textarea`/`x-select` (kein Formular braucht sie), `x-empty-state` (2 Leerzustände, beide einzeilig).

---

## 6. Layout-, Mobile- und Interaktivitäts-Audit

### Layout

Ein Shell. `layouts/app.blade.php` mit einem `$wide`-Schalter, der den Frame (`mx-auto w-full max-w-frame px-4 sm:px-6 lg:px-8`) zwischen aussen und innen verschiebt — normale Seite einmal aussen gerahmt, Artikelseite rahmt Header/Next-Page/Footer einzeln, damit die Lesespalte eigene Breiten setzen kann. Der Frame-String steht genau einmal (`:49`). Nichts zu kollabieren.

Nebenschauplatz: `demo/_layout.blade.php`, `demo/_app_layout.blade.php` und `demo/start/_layout.blade.php` sind drei weitere Shells — sie gehören zum Prototypenordner und verschwinden mit ihm (Befund 2.2).

### Mobile — mental geprüft bei 360 / 768 / 1024 px

| | Status |
|---|---|
| Viewport-Meta | ✅ `layouts/app.blade.php:8` |
| Horizontales Page-Scrolling | ✅ `body { overflow-x: clip }` (`app.css:236`) fängt die `w-screen`-Full-Bleed-Bänder von Page-Header, Footer und CTA-Band ab |
| Tabellen | ✅ beide in `overflow-x-auto`; `.news-prose > table` ebenfalls (`app.css:523`) |
| Type-Skalierung | ✅ fluide Tokens — kein `md:`-Step nötig; ⚠️ ausser `text-2xl`/`text-3xl` (3.3) |
| Grid-Ladder | ✅ `x-layout.grid` = `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3` |
| Formular-Actionrow | ✅ `x-form.actions` = `flex-col-reverse` → `sm:flex-row sm:justify-end`, Primäraktion oben auf dem Phone |
| Touch-Targets ≥ 44 px | ✅ überall über `min-h-control`/`size-control`; ⚠️ **eine Ausnahme: Pagination** (2.1) |
| Fixe Pixelbreiten | ✅ keine, die überlaufen — `w-[30rem]` im CTA-Band ist ein `blur`-Dekor hinter `overflow-hidden` |
| Mobile-Nav | ✅ Full-Screen-Dialog, `x-trap.inert.noscroll`, `role="dialog" aria-modal` |
| Drawings (`illustration-row`) | ✅ erst ab `xl` sichtbar — unter 1280 px reiner Text, bewusst |

Keine Seite bricht bei 360 px. Der einzige Kandidat wäre die Analytics-Tabelle, und die versteckt zwei Spalten unter `sm` und scrollt den Rest.

### Interaktivität

Alpine.js CSP-Build **ist bereits installiert und produktiv im Einsatz** — der Prompt fragt danach, die Antwort ist ja. 9 registrierte Komponenten in `resources/js/app.js`: `navigation`, `combobox`, `autoSubmit`, `readingProgress`, `tableOfContents`, `videoEmbed`, `codeBlock`, `tabs`, plus `focus`-Plugin.

Für den Refactor bedeutet das: **keine neue Interaktivität nötig und keine geplant.** Die Pagination-Komponente ist reines HTML, die Tabellen-Komponente ist reines HTML. Ich flagge nichts.

Zwei Dinge, die beim Anfassen zu beachten sind:
- Der CSP-Build kann keine Inline-Expressions auswerten — Logik gehört in `Alpine.data()`. (Der Kommentar in `app.js:161` zu dieser Einschränkung ist übrigens veraltet: Ternaries und Methodenargumente funktionieren, der Parser ist da.)
- `combobox` manipuliert `bg-gray-100` per `classList` (`app.js:63,73`). Die Klasse steht damit nur im JS — wenn sie je aus dem Blade verschwindet, purged Tailwind sie weg. Sie steht aktuell noch in `form/combobox.blade.php:40`, ist also sicher, aber fragil.

---

## 7. Accessibility

**Sauber:** Skip-Link · Breadcrumbs semantisch korrekt (`<nav aria-label>` + `<ol>` + `aria-current="page"`, letzter Krumen kein Link) · Aktivzustand in Desktop- *und* Mobile-Nav über `Str::before()`-Sektionsvergleich, Detailseite hält ihre Sektion hell · `role="alert"`/`role="status"` situativ in `x-ui.alert` · Fehlermeldungen tragen Text, nicht nur Rot · Sprachumschalter mit `hreflang` und echten Links · Social-Icons mit personalisiertem `aria-label` („LinkedIn — Name") · `aria-hidden` auf allen dekorativen Trennern und Drawings · `prefers-reduced-motion` global, plus eine Extraregel für den Tilt (`app.css:823`) · TOC mit `IntersectionObserver` und `aria-current` · Combobox mit vollständigem `role="combobox"`/`listbox`/`option`, `aria-activedescendant`, Home/End/Escape.

**Lücken:**

| # | Befund | Ort |
|---|---|---|
| 7.1 | **Pagination**: Touch-Target 36 px, Focus-Ring blau statt Marke — verletzt WCAG 2.5.8 und bricht optisch aus | Vendor-View (2.1) |
| 7.2 | `border-strong` = gray-500 auf `surface` = **4.63:1**. Als Rahmen (Anforderung 3:1) unkritisch, als Platzhaltertext (`placeholder-hint`, 4.5:1 gefordert) auf einem Panel mit `bg-surface` genau an der Grenze. Aktuell steht kein Input auf `surface` — bleibt so, wenn niemand einen dorthin stellt. | `form/input.blade.php` |
| 7.3 | `red-600` auf `surface` = **4.56:1**. Ebenfalls an der Grenze; die Fehlermeldung trägt Text, das Rot ist nur Verstärkung — vertretbar, aber notiert. | `form/field.blade.php` |
| 7.4 | `x-form.field` verdrahtet `aria-describedby` **nicht automatisch**. `x-form.input` akzeptiert `describedBy`, aber nur *ein* Call-Site setzt es (`network/manage.blade.php:47`). Alle anderen Felder mit `help`-Text lassen den Hinweis für Screenreader unverbunden. | `components/form/field.blade.php` |
| 7.5 | Kein `lang`-Attribut auf gemischtsprachigen Fragmenten (z. B. englische Produktnamen im deutschen Fliesstext). Kosmetisch. | global |

7.4 ist die einzige echte Lücke mit Nutzerwirkung — und sie ist mit ~5 Zeilen in `x-form.field` zu schliessen, indem die Komponente die `-help`-ID an den Slot durchreicht.

---

## 8. Priorisierter Refactor-Plan

Jeder Batch ist einzeln reviewbar und einzeln deploybar. **Kein Batch fasst Routes, Controller, Models oder View-Daten an.**

### Batch 1 — Auslieferungsdefekte (höchste Priorität)
1. `x-ui.pagination` bauen, token-treu, `min-h-control`, `focus-ring`. In `analytics.blade.php:118` einsetzen.
2. `@source`-Zeile für die Vendor-Pagination aus `app.css:95` streichen.
3. `@source '../views'` auf die Produktivverzeichnisse einschränken (`app`, `components`, `layouts`, `errors`, `markdown`).
4. `.demo-marquee`, `.demo-dotgrid`, `@keyframes demo-marquee` aus `app.css` entfernen.

*Erwartetes Ergebnis:* kein `prefers-color-scheme` und keine 53 arbitrary-value-Regeln mehr im Bundle. Gemessene Baseline für den Vorher-Nachher-Vergleich: **119 543 B / 19 635 B gzip**.
*Voraussetzung:* Entscheidung, ob `resources/views/demo/` bleibt. Wenn ja, bleiben die Routen wie sie sind — nur das CSS wird getrennt.

### Batch 2 — Utilities (mechanisch, hoher Hebel)
5. `@utility focus-ring` / `focus-ring-inset` / `tap-target` in `app.css`.
6. 23 Focus-Ring-Strings und 15 Touch-Target-Strings ersetzen; `x-ui.button` von `ring-*` auf `outline-*` umstellen (3.1).
7. 6× `text-gray-500` → `text-muted` (3.2).

*Rein textuell, kein Rendering-Delta ausser dem beabsichtigten Angleich am Button.*

### Batch 3 — Tabelle
8. `x-ui.table` mit einer Konvention (Trenner, Kopf, Padding, Scroll-Container, `hideBelow`-Prop für die Mobile-Spaltenreduktion).
9. Analytics- und Archive-Tabelle darauf migrieren. Löst 17 Padding-Vorkommen auf.

### Batch 4 — Restliche Duplication Map
10. `<x-slot:note>` an `x-layout.page-header`, 3 Call-Sites (3.7).
11. `x-ui.contact-line` + `config('company.*')` als Anzeigequelle, 5 Call-Sites; Kontaktblock in Kontakt und Impressum auf eine Reihenfolge bringen (3.4, 3.5).
12. `--gradient-wash` bzw. `@utility bg-wash`, 3 Call-Sites (3.6).
13. `x-ui.icon-button`, 4 Call-Sites.

### Batch 5 — A11y und Aufräumen
14. `aria-describedby` in `x-form.field` automatisch verdrahten (7.4).
15. `text-2xl`/`text-3xl` auf Tokens ziehen — braucht ggf. ein `--text-metric`-Token für Kennzahlen (3.3). **Entscheidung nötig**, ob neues Token oder `text-title`.
16. Tote Varianten entfernen oder benutzen (3.9).
17. `person-card`/`network-user-card` zusammenführen (3.8). **Grösster Batch, letzter Batch** — 4 + 2 Call-Sites, sichtbare Änderung auf `/team`, `/netzwerk` und am Artikelfuss.

---

## 9. Was ich zur Freigabe brauche

| Frage | Wirkt auf |
|---|---|
| **Bleibt `resources/views/demo/`?** Löschen, oder nur aus dem CSS-Build nehmen? | Batch 1 |
| **`text-2xl`/`text-3xl`**: neues `--text-metric`-Token, oder auf `text-title` ziehen? | Batch 5.15 |
| **Kontaktblock**: Telefon zuerst oder E-Mail zuerst? | Batch 4.11 |
| `person-card`/`network-user-card` zusammenführen — oder als bewusst getrennte Rollen belassen? | Batch 5.17 |

Batch 1 und 2 sind unabhängig von allen vier Fragen und können sofort starten.

---

*Snapshot-Hinweis: `resources/css/app.css` und 30 Blade-Dateien wurden während des Audits verändert (u. a. `tilt-art` → `illustration-row__art`, TOC-Umbau, `page-header` verliert die `illustration`-Prop). Die zitierten Zeilennummern sind gegen den Working Tree von 08:41 geprüft. Vor dem Refactor gegenprüfen.*

---

## 10. Umsetzung

Durchgeführt am 2026-08-01, committet in `e501564`. Zwischen Audit und Umsetzung wurde parallel
weiterentwickelt — drei Befunde hatten sich dadurch bereits erledigt (siehe „Entfallen").

### Messergebnis

| | vorher | nachher |
|---|---:|---:|
| Bundle roh | 119 543 B | **81 179 B** (−32 %) |
| Bundle gzip | 19 635 B | **13 959 B** (−29 %) |
| `dark:`-Regeln | 14 | **0** |
| `@media (prefers-color-scheme)` | 1 | **0** |
| Arbitrary-Value-Regeln im Bundle | 53 | 22 |
| Focus-Ring-Strings in Blade | 26 in 7 Varianten | **1** (begründet) |
| `py-2 pr-4` in Views | 17 | **0** |
| Tests | 361 grün | **390 grün** |

### Dark Mode — restlos entfernt

Zwei Quellen, nicht eine:

1. **Laravels `pagination::tailwind`.** Ersetzt durch `x-ui.pagination` (Marken-Palette,
   `min-h-control`, `focus-ring`, `aria-current="page"`, `aria-label` je Seitenlink). Zusätzlich
   liegt unter `resources/views/vendor/pagination/tailwind.blade.php` ein Einzeiler, der auf die
   Komponente delegiert — damit ist auch ein künftiges `->links()` irgendwo im Code abgesichert.
   Der `@source` auf die Vendor-View ist gestrichen.
2. **Dieser Report selbst.** Tailwind v4 scannt per Automatik das ganze Projektverzeichnis, also
   auch `reports/frontend.md` — und weil Kapitel 2.1 die Klassennamen `dark:text-gray-400`,
   `dark:focus:border-blue-700` und `dark:active:bg-gray-700` wörtlich zitiert, landeten genau
   diese drei Regeln wieder im Bundle. Behoben mit `@import 'tailwindcss' source(none)` plus
   expliziten `@source`-Pfaden auf `../views`, `../js`, `../../app`.

Der zweite Punkt hat einen Nebeneffekt: die Automatik hatte auch Prosa aus `prompts/`, `todos/`
und den News-Markdown-Dateien als Klassenkandidaten eingesammelt (`container`, `grow`, `visible`,
`lowercase`, `size-14` …). Diese Fehltreffer sind jetzt ebenfalls weg — ein guter Teil der
Bundle-Ersparnis stammt daher, nicht nur aus den Demos.

### Neue Utilities (`app.css`)

`focus-ring` · `focus-ring-inset` · `focus-ring-wide` · `focus-ring-light` · `tap-target` · `icon-button`

Ersetzen 26 Focus-Ring- und 14 Touch-Target-/Icon-Button-Vorkommen. `x-ui.button` läuft damit auf
`outline-*` statt `ring-*` — es gibt nur noch einen Focus-Mechanismus.

### Neue Komponenten

`x-ui.pagination` · `x-ui.table` + `x-ui.table.row` + `x-ui.table.cell` · `x-layout.page-note`

Beide Tabellen (Analytics, LLM-Archiv) laufen über die Komponente — eine Konvention für Trenner,
Kopf, Padding, Ausrichtung und Scroll-Container. Die Spaltenreduktion auf dem Phone ist jetzt ein
`:hide="true"` statt eines wiederholten `hidden sm:table-cell`.

### Weitere Änderungen

- 6× `text-gray-500` → `text-muted` (4.84:1 → 7.56:1)
- `stat-card` `text-2xl` und Co-Working-Preis `text-3xl` → `text-title` (fluid 24→30 px)
- Impressum: Telefon und E-Mail kommen aus `config/company.php` statt aus Übersetzungsstrings
- `aria-describedby` verdrahtet sich in `x-form.input` und `x-form.file` automatisch über `@aware`
  (Befund 7.4). Für die zwei Felder mit `<x-slot:help>` ist `described-by` explizit gesetzt —
  `@aware` sieht nur Attribute, keine Slots.
- Tote Varianten entfernt: `x-ui.row` `compact`, `x-ui.badge` `solid`
- Kommentare aus den 14 bearbeiteten Blade-Dateien entfernt
- 5 Übersetzungs-Keys je Sprache für die Pagination

### Bewusst nicht geändert

| | Grund |
|---|---|
| **3.8 person-card / network-user-card** | Zusammenlegen ergäbe zwei disjunkte Layouts hinter einem Namen — 128-px-Porträtkarte vs. 32-px-Zeile. Sie sind unterschiedliche Komponenten, keine Dublette. Bleibt offen, auf Zuruf machbar. |
| Emerald-Focus-Ring in `x-intro` (2×) | Das Terminal-Widget steht auf `zinc-950`; Markenlila hätte dort 1.4:1. Eigene Palette, unter Rule-of-three. |
| `x-ui.social-links` behält `size-control … sm:size-8` | Wechselt die Grösse am Breakpoint. Mit `icon-button` würde die Reihenfolge der Emission entscheiden, welche Grösse gewinnt — genau die Falle, vor der `app.css` warnt. |
| `bg-wash`-Utility wieder verworfen | Der Logo-Wash steht durch euren Refactor nur noch in `nav-card`. Ein Utility für einen Aufrufer ist Overhead. |
| `x-ui.alert` `success`/`error` | Ungenutzt, aber der semantische Satz gehört zusammen. |
| Navigations-Typo `text-xl`/`text-2xl` | Eigene, in sich konsistente Menü-Skala. |
| `[&_td:not(:last-child)]:pr-4` in `x-ui.table` | Bewusster Arbitrary-Variant an genau einer Stelle — ersetzt eine Prop, die jede Zelle hätte tragen müssen. |

### Entfallen — durch Parallelarbeit erledigt

- **3.4 / 3.5 Kontaktblock doppelt**: Die Kontaktseite ist auf Buttons mit `config()`-Werten
  umgebaut. Nur das Impressum hing noch an hartcodierten Strings.
- **3.6 Logo-Wash 3×**: TOC und Öffnungszeiten nutzen ihn nicht mehr.
- **Demo-Prototypen (2.2)**: Views, Routen und Controller sind bereits gelöscht; hier fielen nur
  noch `demo-marquee` / `demo-dotgrid` aus `app.css`. Eine eigene Demo-CSS-Datei war damit
  gegenstandslos — es gibt keine Demos mehr, die sie laden würden.

### Verifikation

`npm run build` · `responsecache:clear` · `view:clear` · `php artisan test --parallel` → 390 grün.
Zusätzlich alle 33 GET-Routen gerendert und auf Status, `dark:`, `prefers-color-scheme`,
`py-2 pr-4`, `text-gray-500` und die alte Seiten-Notiz geprüft — sauber. Pagination, Tabellenkopf
und die `aria-describedby`-Verdrahtung wurden im gerenderten HTML gegengelesen.
