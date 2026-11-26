# Innovationsmanagement — neuer Bereich auf der Expertise-Seite

**Stand:** 2026-11-21 · nur Konzept. An der Website ist nichts geändert.

> **Hinweis vom 2026-11-21:** Die inhaltliche Ausrichtung hat sich geändert (von der Problemstellung bis zum getesteten Prototyp, Prototypen statt Pilotprojekte, Design Sprint als Methode). Der aktuelle Stand steht in `customer-journey.md`. Dieses Dokument beschreibt die erste Fassung; gültig bleiben Abschnitt 3 (Illustrationen) und Abschnitt 4 (was bei einer Umsetzung anzupassen wäre).

## Überblick

| | |
|---|---|
| Was | Fünfter Bereich «Innovationsmanagement» auf `/dienstleistungen` (EN `/services`) |
| Wo | An erster Stelle, vor «Konzeption & Prototyping» |
| Aussage | Aus vielen Ideen die richtigen auswählen und im Kleinen prüfen, bevor investiert wird |
| Schlagworte | Ideenbewertung · Pilotprojekte · Roadmap |
| Illustrationen | Entworfen: Banner, Karte und Vorschaubild für den Bereich, dazu das Vorschaubild der Expertise-Seite mit fünf statt vier Stationen |
| Folgen | «Vier Bereiche, ein Weg» würde überall «Fünf Bereiche, ein Weg»; die Startseite würde den Bereich im Tab «was-wir-machen» listen |
| Sprachen | Deutsch und Englisch |
| Offen | Sechs Punkte, siehe Abschnitt 6. Der wichtigste: Das Angebot selbst ist mein Vorschlag |

| Datei | Inhalt |
|---|---|
| `innovationsmanagement.html` | So sähe die Expertise-Seite mit dem neuen Bereich aus |
| `customer-journey.md` | Der ganze Weg von der Expertise-Seite bis zur Reservation eines Design Sprints, mit den Seiten `innovationsmanagement-detail.html` und `design-sprint.html` |
| `innovationsmanagement-startseite.html` | So sähe die Startseite aus, Tab «was-wir-machen» mit fünf Bereichen |
| `innovationsmanagement/texte/` | Vollständiger Text des Bereichs, Deutsch und Englisch |
| `innovationsmanagement/illustrationen/` | Die Entwürfe der Illustrationen und ihr Manifest-Eintrag |

Die HTML-Dateien lassen sich direkt von der Festplatte öffnen. Sie sind Momentaufnahmen; die lokale Website zeigt den Bereich nicht.

---

## 1. Einordnung

Die Expertise-Seite beschreibt einen Weg: von der ersten Idee über Konzept und Umsetzung bis zum Betrieb. Bisher beginnt dieser Weg bei «Konzeption & Prototyping», also dort, wo bereits feststeht, welche Idee verfolgt wird. Innovationsmanagement setzt einen Schritt früher an: Welche Idee lohnt sich überhaupt?

Deshalb steht der Bereich an erster Stelle. Die Abgrenzung zur Konzeption:

| | Innovationsmanagement | Konzeption & Prototyping |
|---|---|---|
| Ausgangslage | Viele Ideen, keine Auswahl | Eine Idee, noch kein Konzept |
| Arbeit | Sammeln, bewerten, priorisieren, im Pilotprojekt prüfen | Anforderungen schärfen, Prototyp bauen |
| Ergebnis | Roadmap und ein Entscheid pro Idee | Technisches Konzept mit Technologiewahl |

Im Team ist die Rolle bereits besetzt: Alexander Christoph Boll führt «Innovationsmanagement & Projektleitung» als Funktion.

---

## 2. Text auf der Seite

**Titel:** Innovationsmanagement (EN: Innovation management)

**Teaser DE:** Ideen gibt es genug, entscheidend ist die Auswahl. Wir sammeln und bewerten sie mit dir und prüfen die besten in kleinen Pilotprojekten, bevor du investierst.

**Teaser EN:** Ideas are plentiful, the selection is what counts. We collect and rate them with you and test the best in small pilot projects before you invest.

**Schlagworte:** Ideenbewertung, Pilotprojekte, Roadmap

**Startseite, Tab «was-wir-machen»:** Innovationsmanagement – wir bewerten deine Ideen und prüfen die besten in kleinen Pilotprojekten, bevor du investierst.

**Seitenkopf:** Fünf Bereiche, ein Weg: von der ersten Idee über Konzept und Umsetzung bis zum Betrieb, den wir langfristig begleiten.

**Suchmaschinen-Titel:** Innovation, Konzeption, Software, DMS & ERP

**Suchmaschinen-Beschreibung:** Fünf Bereiche, ein Weg: von der ersten Idee bis zum laufenden Betrieb – Innovationsmanagement, Konzeption, Softwareentwicklung, Dokumentenmanagement und ERP.

### Langtext

Liegt in `innovationsmanagement/texte/de_CH.md` und `en_CH.md`. Er wäre heute nirgends sichtbar, weil die Detailseiten der Bereiche stillgelegt sind. Er dient als Quelle für die Illustration und steht bereit, falls die Detailseiten wieder aufgehen.

Aufbau:

1. **Aus vielen Ideen die richtigen machen** — Einstieg
2. **Unser Vorgehen** — Sammeln, Bewerten, Priorisieren, Erproben, Entscheiden
3. **Neue Technologien einordnen** — KI als Beispiel. Sie unterstützt das Team, die Entscheidung bleibt bei den Menschen
4. **Wie es weitergeht** — Übergang zu Konzeption, Entwicklung und Betrieb. Ein gestoppter Pilot gilt auch als Ergebnis

---

## 3. Illustrationen

Alle Bilder folgen `prompts/illustration-services.md`: wortlos, fünf Farbwerte, links ungeordnet und schwarzweiss, rechts geordnet und farbig.

Alle Entwürfe liegen in `innovationsmanagement/illustrationen/`.

| Entwurf | Zweck | Ziel bei der Umsetzung |
|---|---|---|
| `innovationsmanagement.svg` | Banner 1600×840 | `public/images/services/` |
| `innovationsmanagement.png` | Vorschaubild 1200×630, aus dem Banner gerechnet | `public/images/services/` |
| `innovationsmanagement-card.svg` | Karte 344×344 in der Zeile auf der Expertise-Seite | `public/images/services/` |
| `expertise-seite.svg` und `.png` | Vorschaubild der Expertise-Seite mit fünf Stationen | ersetzt `public/images/pages/services.index.*` |
| `illustrations.json` | Die zwei Manifest-Einträge | in `public/images/illustrations.json` übernehmen |

**Der Satz hinter dem Bild:** lose Ideenzettel und eine Glühbirne, die niemand gegeneinander abgewogen hat → nach Nutzen und Aufwand filtern → eine Bewertungsmatrix mit einer gewählten Idee und eine Roadmap, die mit dem Pilotprojekt beginnt.

**Handlung in der Mitte:** Trichter. Die übrigen Bereiche verwenden Zeiger, Klammern, Lupe und Knoten, es gibt keine Doppelung.

**Silhouette:** Zettel, Glühbirne und Achsenkreuz kommen in keinem anderen Bereich vor. Die Zeile sieht neben den vier bestehenden eigenständig aus.

**Angepasstes Vorschaubild der Expertise-Seite:** Links liegt neu ein Ideenzettel bei den vier losen Gegenständen, rechts hängen fünf statt vier Stationen am Weg. Der Titel im Bild lautet «Fünf Bereiche …».

Jeder Gegenstand im Bild zitiert einen Satz aus dem Text des Bereichs. Die Entwürfe haben das Illustrations-Gate bestanden, als sie testweise eingebunden waren.

---

## 4. Was bei der Umsetzung anzupassen wäre

| Ort | Änderung |
|---|---|
| `database/files/services/{de_CH,en_CH}/innovationsmanagement.md` | Die zwei Textdateien aus `innovationsmanagement/texte/` |
| `database/files/services/de_CH/*.md` | Reihenfolge: Innovationsmanagement 1, die vier bestehenden Bereiche 2 bis 5 |
| `lang/de_CH/components.php`, `lang/en_CH/components.php` | «Vier Bereiche» wird «Fünf Bereiche» (Startseite, «Mehr entdecken», Seitenkopf); neuer Eintrag im Tab «was-wir-machen» |
| `database/files/pages/services.index.yaml` | Titel und Beschreibung für Suchmaschinen |
| `prompts/illustration-seo-card.md` | Beschreibung des Vorschaubilds auf fünf Bereiche nachgeführt |
| `tests/Feature/Seeders/ServicesTableSeederTest.php` | Erwartet fünf Bereiche |
| `tests/Feature/Components/IntroTabsTest.php` | Testname nachgeführt |

Kein neuer Code, keine neue Route, keine Migration. Ein Bereich ist zwei Markdown-Dateien plus Bilder.

Die strukturierten Daten (JSON-LD) und die Sitemap brauchen nichts: Der neue Bereich erscheint automatisch als `Service` im Graph der Expertise-Seite.

---

## 5. Bewusst nicht vorgesehen

- **Keine Preise, keine Pakete, keine Dauerangaben.** Dazu gibt es keine Vorgabe.
- **Keine Referenzen.** Es sind keine genannt.
- **Recruiting-Folien** (`resources/views/app/jobs/slides.blade.php`) zeigten weiterhin «Vier Bereiche» mit vier Karten im Raster zwei mal zwei. Eine fünfte Karte verändert das Folienlayout und gehört in einen eigenen Schritt.

---

## 6. Offene Punkte

1. **Inhalt des Angebots.** Sammeln, Bewerten, Priorisieren, Pilotprojekt, Entscheid ist mein Vorschlag, abgeleitet aus der Rolle im Team und dem Weg der Seite. Stimmt das mit dem überein, was ihr tatsächlich anbietet?
2. **Position.** Erste Stelle, weil der Bereich vor der Konzeption ansetzt. Alternative: letzte Stelle als jüngster Bereich, so wie «Open Source ERP» heute.
3. **Schlagworte.** Ideenbewertung, Pilotprojekte, Roadmap. Denkbar wäre auch «Workshops».
4. **KI im Langtext.** Der Absatz nennt KI als Beispiel für neue Technologien. Er ist heute nicht sichtbar, wird es aber mit den Detailseiten.
5. **Ansprechperson.** Alexander Boll könnte bei diesem Bereich genannt werden. Kein anderer Bereich nennt eine Person, deshalb ist es nicht eingebaut.
6. **Suchmaschinen-Titel.** «Innovation, Konzeption, Software, DMS & ERP» ist länger als bisher. Kürzere Variante: «Expertise: fünf Bereiche, ein Weg».

---

## 7. Veröffentlichung

1. Abschnitt 4 umsetzen, committen und auf `main` pushen; der Release-PR `main` → `production` entsteht oder wird nachgeführt.
2. Auf Produktion vor dem Merge: `php artisan services:import` und `php artisan pages:import`.
3. Danach `php artisan responsecache:clear`.
4. Live prüfen: `/dienstleistungen` und `/services` zeigen fünf Bereiche, `og:image` der Expertise-Seite zeigt fünf Stationen.
