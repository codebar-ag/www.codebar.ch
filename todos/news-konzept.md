# News → Publikation: Konzept

Stand: 28.07.2026 · Branch `feature-updates`

## Kontext

Der News-Bereich ist heute eine Liste, keine Publikation. Konkret:

- **Übersicht** (`resources/views/app/news/index.blade.php`, 17 Zeilen): ungefilterte, unpaginierte Liste
  aus `x-card.item-card` — Titel, Teaser, Tag-Badges. Keine Bilder, keine Daten, keine Lesezeit.
- **Artikelseite** (`resources/views/app/news/show.blade.php`, 26 Zeilen): Tags, H1, Teaser, Prosa,
  darunter ein Block „Meta-Informationen" mit drei Badges. Kein Hero, keine Autorenangabe mit Gesicht,
  kein Inhaltsverzeichnis, keine Weiterführung.
- **Datenmodell** (`database/migrations/2025_04_04_181258_create_news_table.php`): `title`/`teaser`/`content`
  als übersetzbare JSON-Spalten, `slug` (unique, **für beide Sprachen derselbe**), `image` (wird nirgends
  gerendert), `author` (Freitext), `tags` (flaches JSON-Array, nur Deko).
- **Redaktion**: kein CMS. Markdown-Datei nach `database/files/news/{locale}/`, Eintrag von Hand in
  `database/seeders/NewsTableSeeder.php`, `php artisan db:seed`, deployen.
- Kein Feed, keine Themenseiten, keine Serien, keine Autorenprofile, keine Suche.

Ziel ist eine Leseerfahrung auf dem Niveau von Medium: lange Artikel müssen gut aussehen **und** gut zu
lesen sein, Artikel sollen sich zu mehrteiligen Geschichten verbinden lassen, und die Redaktion muss ohne
Seeder-Chirurgie arbeiten können.

## Entscheidungen

| Thema | Entscheidung |
|---|---|
| Redaktion | Markdown + YAML-Front-Matter, Import per Artisan-Command (kein Admin-Panel) |
| Inhaltsbausteine | Bild, Galerie/Vergleich, Zitat, Hinweisbox, Code, Tabelle, Schritt-für-Schritt, Video |
| Bilder | Cloudinary (`CloudinaryUrl` wird erweitert) |
| Gestaltung | Eigene Editorial-Ebene nur für News; Navigation, Footer, Marke bleiben identisch |
| Schrift | **Nur Poppins**, Schnitte 400/600/700 echt geladen. Eine Serifenschrift wurde eingeführt und wieder verworfen — zwei Schriftarten wirkten fremd zum Rest der Website |
| Serien | Geordnete Serie („Teil 2 von 5") **plus** freie Querverweise |
| Artikelseite | Kategorie/Datum/Lesezeit über dem Titel, Autor unter dem Titelbild, Inhaltsverzeichnis + Lesefortschritt, Weiterlesen. Keine Teilen-Funktion |
| Übersicht | Leitartikel + Bildkarten, Themenfilter über `?thema=`, später Serien-Übersicht, Blättern, RSS |
| Autor | Verknüpfung auf bestehende `contacts` — keine neuen Felder, ein Autor pro Artikel |
| Startseite | Drei Artikel als `x-card.item-card`-Liste — dieselben Komponenten wie der Rest der Startseite. Ein Leitartikel mit grossem Bild wurde probiert und verworfen: die Startseite trägt sonst keinerlei Bildsprache |
| Sprachen | Slug pro Sprache, jeder Artikel liegt in beiden Sprachen vor |
| Vorgehen | Erst Design entscheiden (10 Varianten), dann in vier Etappen bauen |
| Design-Richtung | **«Editorial Quiet»** — Mischung aus 01 «Editorial Serif» und 03 «Quiet Reader», siehe unten |

## Gewählte Design-Richtung

Aus zehn Entwürfen gewählt; die Explorationsseiten unter `/demo/news` sind nach der
Entscheidung entfernt worden. Was von wo kommt:

**Aus 01 «Editorial Serif»**
- Leitartikel mit 3:1-Bild auf der Übersicht, Haarlinien als Trenner
- Vollständige Teileliste bei Serien
- ~~Serifenschrift für Titel und Fliesstext~~ — probiert und verworfen, siehe unten
- ~~Rasterspalte mit drei Breiten~~ — probiert und verworfen, alles teilt sich eine Breite

**Aus 03 «Quiet Reader»**
- Leseordnung: Titel zuerst, Bild danach — nicht randloser Hero über allem
- Autorenzeile mit Gesicht, direkt unter dem Titel und in der Übersichtsliste
- Grosszügiger Weissraum, kaum Rahmen; Autorenbox und Hinweisboxen ohne harte Umrandung
- Weiche Ecken (`rounded-panel`) bei Vorschaubildern
- Zentrierte Bildlegenden, zentriertes Pull-Quote
- Pill-Filter für Themen, Fortschrittsbalken bei Serien

### Zwei Entscheide, die im Bauen gekippt sind

- **Zweite Schriftart verworfen.** Source Serif Pro war eingebunden und lief; nebeneinander
  mit Poppins wirkte der News-Bereich aber wie eine fremde Website. Jetzt trägt Poppins
  alles, im Artikel nur grösser (18 px) und luftiger (1.75) gesetzt als sonst.
- **Ausbrechende Elemente verworfen.** Text, Bilder, Tabellen und Inhaltsverzeichnis teilen
  sich eine einzige Breite.

## Datenmodell

### `news` — Änderungen

```
+ slug            json      (übersetzbar, ersetzt string; unique je Sprache über Index auf generierter Spalte)
+ hero_image      string?   (Cloudinary Public-ID oder URL)
+ hero_caption    json?     (übersetzbar)
+ hero_alt        json?     (übersetzbar)
+ contact_id      FK → contacts.id, nullable  (Autor)
+ series_id       FK → news_series.id, nullable
+ series_position smallint?
+ featured        bool, default false          (Leitartikel auf Übersicht/Startseite)
+ reading_minutes smallint?                    (beim Import berechnet, nicht live)
~ author          bleibt als Fallback für Artikel ohne contacts-Verknüpfung
- image           entfällt zugunsten hero_image (Migration überträgt bestehende Werte)
```

**Veröffentlichung** braucht beides: ein `published_at`-Datum **und** `published = true`.
Die Trennung existiert, damit ein Artikel offline genommen werden kann, ohne sein
Publikationsdatum zu verlieren — nach diesem Datum wird sortiert und es steht im Artikelkopf.

Zum Ausblenden also `published: false` ins Front-Matter schreiben und `news:import` laufen
lassen. Der Artikel verschwindet dann aus Übersicht, Startseite, Sitemap, Serien-Navigation
und „Weiterlesen"; die URL antwortet mit 404.

### `news_series` — neu

```
id, key (unique), title (json), description (json), slug (json), published, created_at, updated_at
```

### `news_relations` — neu (freie Querverweise)

```
id, news_id FK, related_news_id FK, sort
```
Unidirektional gepflegt, im Frontend beidseitig gelesen.

### `news_tags` / `news_tag` — neu

Tags werden aus dem heutigen JSON-Array in eine Tabelle mit `key`, `title` (json), `slug` (json),
`description` (json) überführt, damit Themenseiten eigenen Titel, eigene Beschreibung und eigene
SEO-Auszeichnung bekommen. Der Import legt fehlende Tags automatisch an.

### `contacts`

Autor wird über `contact_id` verknüpft. Name, Bild (Cloudinary), Rolle je Sprache und LinkedIn kommen
aus `sections.{section}.role.{locale}` bzw. `icons` — siehe `app/DTO/ContactDTO.php`.

Nachträglich ergänzt: `key` (unique, stabiler Griff für die Dateien) und `sort` (Reihenfolge auf
`/ueber-uns`, ersetzt die alphabetische Sortierung). Die Personendaten liegen seither als
**eine YAML-Datei pro Person** unter `database/files/team/` — Dateiname `<key>.yaml`,
eingelesen mit `php artisan team:import`
— dieselbe Idee wie bei News, nur ohne Markdown-Rumpf, weil es keinen Fliesstext gibt.
Die frühere `database/seeders/data/contacts.csv` (semikolongetrennt, mit JSON in den Zellen) ist
entfernt. Fehlt eine Datei, wird die Person beim nächsten Import gelöscht — die Dateien sind
die Quelle der Wahrheit.

## Redaktionsprozess

Pro Artikel und Sprache eine Datei. **Namensschema: `JJJJ-MM-TT-<key>.md`** — beide
Sprachdateien tragen denselben Namen, damit ein Verzeichnislisting nach Datum sortiert und
das Paar zusammensteht. Der Import warnt, wenn ein Name abweicht:

```
database/files/news/de_CH/2026-07-28-dms-migration-2.md
database/files/news/en_CH/2026-07-28-dms-migration-2.md
```

```markdown
---
key: dms-migration-2          # sprachübergreifende Klammer, verbindet de/en
slug: dms-migration-datenbereinigung
title: Datenbereinigung vor der Migration
teaser: Warum der Aufwand vor dem Umzug über den Erfolg danach entscheidet.
published_at: 2026-07-28
author: 1                     # contacts.id
hero: www-codebar-ch/news/dms-migration/hero
hero_alt: Aktenschrank mit sortierten Ordnern
hero_caption: Ordnung vor dem Umzug spart Wochen danach.
series: dms-migration
series_position: 2
tags: [dms-ecm, docuware, migration]
featured: true
---

Fliesstext …

:::figure{src="www-codebar-ch/news/dms-migration/workflow" width="wide" alt="Der Workflow-Designer"}
Der neue Workflow-Designer — Schritte lassen sich per Drag & Drop verschieben.
:::

:::callout{type="warning"}
Vor dem Update unbedingt ein Backup ziehen.
:::

:::quote{cite="Sebastian Bürgin-Fix"}
Die Migration ist nie das Problem. Die Daten sind es.
:::
```

`php artisan news:import` liest alle Dateien, validiert das Front-Matter, berechnet die Lesezeit,
legt fehlende Tags/Serien an, schreibt per `key` und leert die Caches. Der `NewsTableSeeder`
ruft den Import auf, damit `db:seed` weiter funktioniert.

Optionen: `--dry-run` zeigt die Änderungen ohne zu schreiben, `--key=` importiert einen
einzelnen Artikel, `--path=` liest aus einem anderen Verzeichnis (wird von den Tests genutzt).

Fehlt eine Sprache, wird der Artikel gemeldet und **übersprungen**, und der Command endet mit
Exit-Code 1 — ein halb übersetztes Paar würde ein hreflang-Paar erzeugen, das zwei verschiedene
Artikel verbindet.

Front-Matter-Felder: `key` (sprachübergreifende Klammer, Pflicht), `slug`, `title`, `teaser`,
`published_at`, `published` (Standard `true`), `author` (contacts-ID oder E-Mail),
`author_name` (Fallback ohne Verknüpfung), `hero`, `hero_alt`, `hero_caption`, `series`,
`series_position`, `series_title`, `tags`, `featured`, `related` (Liste von `key`s).

## Inhaltsbausteine

Als CommonMark-Erweiterung (`app/Markdown/`), die `:::name{attr}` in Blade-Komponenten übersetzt.
Damit bleibt das Aussehen im Design-System und nicht im Artikel.

| Direktive | Rendert |
|---|---|
| `:::figure{src width=text\|wide\|full alt}` | `<figure>` mit `srcset`, `width`/`height`, Bildlegende |
| `:::gallery{cols=2\|3}` / `:::compare` | Bildraster bzw. Vorher/Nachher mit gemeinsamer Legende |
| `:::quote{cite}` | Pull-Quote |
| `:::callout{type=tip\|warning\|info\|summary}` | Farbige Hinweisbox mit Icon |
| `:::steps` | Nummerierte Schrittliste |
| `:::video{src poster}` | Klick-zum-Laden-Einbettung (kein Drittanbieter-Request vor dem Klick) |
| Standard-Markdown | Tabellen scrollbar gekapselt, Code mit Highlighting |

**Sicherheit**: Der Konverter läuft künftig mit `html_input: escape` und `allow_unsafe_links: false`.
Heute wird rohes HTML aus der Markdown-Datei ungefiltert durchgereicht (`Str::of()->markdown()` →
`{!! $content !!}`). Die eine bestehende Datei enthält handgeschriebenes `<ul>`/`<a>` und wird beim
Import auf Direktiven umgestellt.

## Frontend

### Editorial-Ebene

Das globale `max-w-4xl` in `resources/views/layouts/app.blade.php` bleibt unangetastet. Für News kommt
ein eigener Wrapper mit drei Breiten:

- **Text** ~ 680 px — Fliesstext, Listen, Zitate
- **Wide** ~ 1040 px — ausbrechende Bilder, Galerien, Tabellen
- **Full** — randlos, nur Hero und ausgewählte Grafiken

Umsetzung über ein CSS-Grid mit benannten Spalten auf dem Artikel-Container, nicht über negative Margins —
das bleibt bei Zoom und auf kleinen Viewports stabil.

**Alle Elemente teilen sich eine Breite: 52 rem (832 px)** — die Innenbreite des Headers.
Der erste Buchstabe eines Absatzes steht unter dem ersten Buchstaben des Logos, und Text,
Bilder, Tabellen und Inhaltsverzeichnis beginnen und enden gemeinsam. Es gibt keinen
Ausbruch mehr; `width="wide"` bleibt im Front-Matter gültig, ändert aber nichts.

Die Spur ist `min(52rem, 100% - 2rem)` und nicht `minmax()`: sonst schrumpft die
Lesespalte schon, während in den Rändern noch Platz ist.

**Nichts steht seitlich neben dem Fliesstext.** Das Inhaltsverzeichnis ist ein Panel über
dem Artikel, bündig mit der Lesespalte, die Punkte untereinander. Eine mitlaufende
Randschiene, ein mehrspaltiges Band und eine über die Textbreite hinausragende Variante
wurden alle verworfen.

**Metadaten stehen an einer Stelle, nicht verteilt.** Auf der Artikelseite trägt die
Kicker-Zeile über dem Titel Kategorie, Datum und Lesezeit; die Zeile darunter nur den
Autor. Auf den Übersichtskarten stehen Autor, Datum und Lesezeit gemeinsam unter dem
Teaser.

Am Artikelende stehen Tags und Autorenbox — **keine Teilen-Funktion**.

### Referenzartikel

`database/files/news/{de_CH,en_CH}/2026-07-28-bausteine.md` zeigt jeden Baustein einmal in
echt und dient als Vorlage beim Schreiben: `/aktuelles/de_CH/bausteine-im-ueberblick`.
Er ist der einzige verbliebene Demo-Inhalt.
Er ist veröffentlicht und erscheint damit auf der Übersicht und in der Sitemap. Entfernen:
Dateien löschen, `News::where('key', 'bausteine-styleguide')->delete()`, `news:import`.

### Zwei Fallen, die schon zugeschlagen haben

- **`.news-prose` ist ein Grid, und Grid kollabiert keine Margins.** Abstände deshalb nur
  auf einer Seite setzen (`> * + *  { margin-block-start }`), sonst addieren sich
  Unter- und Oberkante zum doppelten Wert.
- **Typografie gehört an `.news-prose`, nicht an `.news-body`.** `.news-body` ist nur das
  Raster; alles, was dort hängt, erben auch Byline, Tags und Autorenbox.
- **`img { height: auto }` muss in `@layer base` stehen.** Eine ungelayerte Regel schlägt
  jede Tailwind-Utility; `size-14` blieb wirkungslos und Flex zog Avatare zu Ovalen.

Überschriften unterscheiden sich vom Fliesstext über Grösse und Gewicht, nicht über eine
zweite Schriftart.

### Typografie

- Poppins 400 / 600 / 700 als echte woff2, latin + latin-ext, unter `public/fonts/poppins/`
  (vorher wurde alles ausser 400 vom Browser verzerrt)
- Lesegrösse 18 px / `line-height: 1.75` im Artikel, sonst wie auf der übrigen Website
- `font-src` in `app/Security/Presets/MyCspPreset.php` erlaubt `self` — keine CSP-Änderung nötig

### Artikelseite

Kopf: Kicker (Serie oder Haupt-Tag) · Datum · Lesezeit — dann Titel, Teaser, Titelbild
und erst darunter die Autorenzeile (Bild, Name, Rolle). Der frühere Meta-Badge-Block am
Seitenende entfällt.

Seitlich: Inhaltsverzeichnis aus `h2`/`h3`, auf `lg` mitlaufend, darunter Teilen-Links
(LinkedIn, E-Mail, Link kopieren — reine Links, kein Drittanbieter-Skript). Auf Mobile ein
aufklappbares `<details>`. Der Lesefortschritt ist ein 2 px-Balken und wird über eine
Alpine-Komponente `readingProgress` in `resources/js/app.js` registriert — **keine Inline-Ausdrücke**,
das Projekt nutzt `@alpinejs/csp`.

Fuss: Serien-Navigation (Fortschritt, zurück/weiter, alle Teile) · verwandte Artikel
(erst `news_relations`, dann nach Tag-Überschneidung aufgefüllt) · Autorenbox · CTA-Band.

### Übersicht

Leitartikel (`featured`, sonst neuster) gross mit Bild, darunter Raster aus Bildkarten mit
Datum, Lesezeit, Tag. Themenfilter als echte Links auf `/aktuelles/thema/{slug}`.
Blättern über `paginate(12)` — die Pagination-Views sind in `resources/css/app.css` bereits
als `@source` registriert. Serien-Übersicht unter `/aktuelles/serien`.

### Startseite

`x-news.latest` steht zwischen `<x-intro/>` und `<x-explore/>` in
`resources/views/app/start/index.blade.php`: `x-h2`, „Alle Artikel →" und drei Artikel als
`x-layout.list` mit `x-card.item-card` — genau die Komponenten, die die Startseite ohnehin
nutzt. Bewusst ohne Bilder: die Startseite hat sonst keine.

## SEO

- `og:type` wird auf Artikelseiten `article` statt des heute hartkodierten `website`
  (`resources/views/layouts/_partials/_seo.blade.php`), plus `article:published_time`,
  `article:modified_time`, `article:author`, `article:tag`
- `SchemaNodes::blogPosting()` (`app/Seo/SchemaNodes.php`) wird um `author` als echte `Person`
  mit `contacts`-Bild, `image` als Hero, `wordCount`, `timeRequired` und `isPartOf` → Serie ergänzt
- Neue Knoten: `newsSeries()` (`CreativeWorkSeries`) und `newsTag()` (`CollectionPage`)
- `SitemapController` bekommt Themen- und Serienseiten; die Priorität von `news.show` steigt auf 0.8
- RSS/Atom unter `/aktuelles/feed.xml` und `/news/feed.xml`, verlinkt per
  `<link rel="alternate">` im Layout

## Caching

Heute wird `news_published_{locale}` per `Cache::rememberForever` gehalten und **nie invalidiert** —
ein neu eingespielter Artikel erscheint in der Sitemap sofort, auf `/aktuelles` aber nicht
(`app/Actions/ViewDataAction.php:48`). Zusätzlich cached `spatie/laravel-responsecache` das fertige HTML.

Lösung: ein `NewsObserver` nach dem Vorbild von `app/Models/Contact.php:32` und
`app/Observers/NetworkUserObserver.php`, der bei `saved`/`deleted` die News-Caches je Locale
und den Response-Cache leert. Der Import-Command ruft dasselbe am Ende auf.

## Etappen

1. **Fundament** ✅ — Migrationen, Modelle, `news:import`, Markdown-Erweiterung, Observer, Tests.
2. **Artikelseite** ✅ — Editorial-Layout, Typografie, Bausteine, Kopf/Fuss, Inhaltsverzeichnis,
   Lesefortschritt, Teilen, Serien-Navigation. Die Übersicht wurde gleich mitgezogen
   (Leitartikel + Bildkarten), damit `/aktuelles` nicht halbfertig aussieht.
3. **Übersicht** ✅ teilweise — Themenfilter läuft über `?thema={slug}` auf der Übersicht
   (kanonische URL bleibt `/aktuelles`). Offen: eigene Themenseiten, Serien-Übersicht, Blättern.
4. **Verteilung** ✅ teilweise — Startseiten-Komponente steht (`x-news.latest`).
   Offen: RSS, SEO-Ausbau (`og:type=article`, `isPartOf` für Serien, `timeRequired`),
   Sitemap um Themen- und Serienseiten erweitern.

Jede Etappe ist einzeln lauffähig und deploybar.

## Offene Punkte aus Etappe 1/2

- **`:::video` braucht eine CSP-Anpassung.** `frame-src` fällt heute auf `default-src 'self'`
  zurück, ein eingebetteter YouTube-/Vimeo-Player wird also blockiert. Sobald der erste
  Artikel ein Video enthält, muss der Host in `app/Security/Presets/MyCspPreset.php` ergänzt
  werden. Der Klick-zum-Laden-Mechanismus steht bereits.
- **`news.tags` bleibt doppelt geführt.** Die JSON-Spalte ist die Anzeige- und
  schema.org-Quelle, `news_tags` die Basis für Themenseiten. Der Import hält beide synchron.
- **`og:type` ist weiterhin `website`**, auch auf Artikelseiten — bewusst auf Etappe 4 verschoben.
- **Team-Avatare unter 96 px:** Cloudinary lehnt `e_background_removal` bei kleineren
  Anforderungen mit 400 ab, deshalb werden Autorenbilder mindestens mit `w_96` geholt.
- **Vite meldet beim Build**, dass `/fonts/...` nicht zur Buildzeit aufgelöst wird. Das ist
  korrekt so: die Schriften liegen in `public/` und werden zur Laufzeit ausgeliefert.

## Prüfung

- `./vendor/bin/pest` — bestehende Suites `tests/Feature/Seo/`, `tests/Feature/Controllers/`,
  `tests/Unit/Actions/` decken News bereits ab und müssen grün bleiben
- Neu: Import-Command (Front-Matter-Validierung, Idempotenz), Markdown-Direktiven,
  Serien-Navigation an den Rändern (Teil 1, letzter Teil), 404 für unveröffentlichte Artikel,
  Cache-Invalidierung
- `vendor/bin/phpstan analyse` — Projekt läuft auf Level 10
- `tests/lighthouse/run.sh news_index news_show` — **nur gegen den Build**, nie gegen den
  Vite-Dev-Server (sonst CLS-Artefakt von 0.8–1.0 statt der realen ~0.01)
