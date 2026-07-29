---
key: bausteine-styleguide
slug: bausteine-im-ueberblick
title: Alle Bausteine im Überblick
teaser: >-
  Dieser Artikel existiert, um jeden verfügbaren Inhaltsbaustein einmal in echt zu zeigen.
  Er dient als Referenz beim Schreiben — und als Kontrolle, dass das Design überall trägt.
published_at: 2026-07-28
published: false
author: sebastian.buergin@codebar.ch
hero: images/templates/cover-template.jpg
hero_alt: Platzhaltergrafik im Seitenverhältnis 3:1
hero_caption: Das Titelbild steht in derselben Breite wie alles andere auf der Seite.
tags: [Styleguide, Redaktion]
featured: false
---

Der erste Absatz ist der Einstieg. Die ganze Website nutzt eine einzige Schrift —
Poppins —, im Artikel nur etwas grösser und luftiger gesetzt. **Fetter Text** und *kursiver Text* funktionieren wie gewohnt,
ebenso [Links](https://www.codebar.ch) und `Inline-Code`.

## Überschrift der zweiten Ebene

Zwischentitel unterscheiden sich vom Fliesstext über Grösse und Gewicht, nicht über eine
zweite Schriftart.

### Überschrift der dritten Ebene

Unterabschnitte erscheinen im Inhaltsverzeichnis eingerückt und ohne Nummer.

- Ein Listenpunkt.
- Ein zweiter Listenpunkt, der lang genug ist, um über mehr als eine Zeile zu laufen und
  damit den Zeilenabstand innerhalb eines Punktes zu zeigen.
    - Ein verschachtelter Punkt.
    - Noch einer.

1. Nummeriert geht auch.
2. Zweiter Punkt.

## Bilder

Alle Elemente teilen sich eine Breite — Bilder beginnen und enden dort, wo der
Fliesstext beginnt und endet:

:::figure{src="images/templates/cover-template.jpg" width="text" alt="Platzhalter in Textbreite"}
Eine Bildlegende steht zentriert unter dem Bild.
:::

`width="wide"` bleibt als Angabe gültig, ändert aber nichts mehr an der Breite:

:::figure{src="images/templates/cover-template.jpg" width="wide" alt="Platzhalter in Ausbruchsbreite"}
Dieselbe Breite wie das Bild darüber.
:::

## Galerie und Vergleich

:::gallery{cols="3" caption="Eine Galerie aus drei Bildern."}
- src: images/templates/cover-template.jpg
  alt: Erstes Bild
  caption: Erstes Bild
- src: images/templates/cover-template.jpg
  alt: Zweites Bild
  caption: Zweites Bild
- src: images/templates/cover-template.jpg
  alt: Drittes Bild
  caption: Drittes Bild
:::

:::compare{caption="Zwei Zustände direkt nebeneinander."}
- src: images/templates/cover-template.jpg
  alt: Zustand vorher
  caption: Vorher · 412 000 Dokumente
- src: images/templates/cover-template.jpg
  alt: Zustand nachher
  caption: Nachher · 268 000 Dokumente
:::

## Zitat

:::quote{cite="Sebastian Bürgin-Fix"}
Die Migration ist nie das Problem. Die Daten sind es.
:::

## Hinweisboxen

:::callout{type="info"}
Ein neutraler Hinweis für Zusatzinformationen, die den Lesefluss nicht unterbrechen sollen.
:::

:::callout{type="tip" title="Aus der Praxis"}
Ein Tipp aus einem echten Projekt.
:::

:::callout{type="warning" title="Vor dem ersten Lauf"}
Ein vollständiges, wiederherstellbares Backup — und ein dokumentierter Test der
Wiederherstellung. Ein Backup, das nie zurückgespielt wurde, ist eine Annahme.
:::

:::callout{type="summary"}
Eine Zusammenfassung am Ende eines längeren Abschnitts.
:::

## Schritt für Schritt

:::steps
- title: Inventar erstellen
  body: Alle Ablagen erfassen, Volumen und Dateitypen zählen, Zugriffe der letzten 24 Monate auswerten.
- title: Regeln festlegen
  body: Pro Dokumentart entscheiden — übernehmen, archivieren oder verwerfen. Schriftlich, mit einer verantwortlichen Person.
- title: Probelauf fahren
  body: Zehn Prozent des Bestandes migrieren und gegen die Regeln prüfen, bevor der Rest folgt.
- title: Abgleich dokumentieren
  body: Zählstände vorher und nachher gegenüberstellen und die Differenz erklären können.
:::

## Code

```sql
SELECT checksum, COUNT(*) AS copies
FROM documents
WHERE archived_at IS NOT NULL
GROUP BY checksum
HAVING COUNT(*) > 1
ORDER BY copies DESC;
```

## Tabelle

| Kategorie | Anteil | Behandlung |
| --- | --- | --- |
| Aktiv genutzt (24 Monate) | 12 % | Direkt übernehmen |
| Aufbewahrungspflichtig | 41 % | Mit Frist übernehmen |
| Dubletten | 19 % | Zusammenführen |
| Ohne Rechtsgrund | 28 % | Nach Freigabe verwerfen |

## Schluss

Damit sind alle Bausteine einmal durch. Wer einen neuen braucht, ergänzt ihn in
`app/Markdown/NewsMarkdown.php` und legt das Template unter `resources/views/markdown/` ab.
