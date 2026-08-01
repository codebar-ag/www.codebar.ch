# Langfrist — Positionierung, Belege, Angebotstiefe

**Stand:** 2026-08-01, Branch `feature-updates`
**Verhältnis zu [`user-journey.md`](user-journey.md):** Dort stehen Korrekturen an der bestehenden Basis — Dinge, die heute unrund sind und sich in Tagen beheben lassen. Hier stehen die Themen, die eine bewusste Richtungsentscheidung voraussetzen und schrittweise über Monate wachsen.

---

## 0. Ausgangslage

Der Verzicht auf Call-to-Actions, Produktseiten, Kundenreferenzen und Case Studies war **jahrelang eine bewusste Entscheidung**, keine Lücke. Dieses Dokument behandelt sie deshalb nicht als Fehler, sondern als Positionierung, die schrittweise geöffnet werden soll.

Das ist auch der Grund, warum die Reihenfolge hier anders funktioniert als in einem klassischen Conversion-Audit: Es geht nicht darum, möglichst schnell möglichst viele Abschlusspunkte zu setzen, sondern darum, in welcher Reihenfolge sich die Öffnung glaubwürdig aufbaut. Ein CTA ohne Beleg dahinter wirkt aufdringlich; ein Beleg ohne Angebotstiefe führt ins Leere. Die Sortierung unten folgt dieser Abhängigkeit.

**Leitplanken, die dabei gelten:**

- **Kein Kontaktformular.** Bewusste Entscheidung. Kontaktaufnahme läuft über Telefon und E-Mail — die Aufgabe ist, diese beiden Wege sichtbarer und einladender zu machen, nicht sie durch ein Formular zu ersetzen.
- **Der Ton bleibt.** Duzen, keine Buzzwords, keine Verkaufsrhetorik. Jede Massnahme hier muss in derselben Stimme funktionieren wie «Wenn nicht, sagen wir es offen».
- **Schrittweise.** Lieber ein Beleg, der stimmt, als fünf, die konstruiert wirken.

---

## 1. Belege aufbauen

**Ist-Zustand.** Auf der gesamten Website existiert kein Beleg: keine Referenz, kein Kundenlogo, kein Projektbeispiel, keine Case Study, kein Testimonial, keine Kennzahl. Die Suche nach «Referenz», «Kunde», «Case Study», «Fallstudie» in Views und Sprachdateien liefert null Treffer.

Das einzige belegartige Material sind die Partnerlogos auf `/netzwerk` (DocuWare, Odoo, iWay, BaselHack) und die beiden Swiss-Made-Software-Labels im Footer — das sind Zugehörigkeiten, keine Ergebnisse.

**Randnotiz aus der Analyse:** In einer älteren, noch im Response-Cache liegenden Fassung des DMS/ECM-Teasers stand «Vom Einzelunternehmen bis zum Konzern mit über 200 Nutzer:innen». Dieser Satz — der einzige quantitative Beleg, den die Website je hatte — ist in der aktuellen Fassung nicht mehr enthalten. Er wäre ein wohlfeiler erster Schritt.

**Warum das der grösste Hebel ist.** Das zentrale Versprechen lautet «klein aus Überzeugung». Das erzeugt beim Gegenüber automatisch die Frage «schaffen die das auch?» — und diese Frage lässt sich ausschliesslich mit Belegen beantworten, nicht mit besserer Formulierung. Ohne sie bleibt die gesamte Argumentation Selbstauskunft.

**Wege, die ohne Kundenfreigabe funktionieren:**

| Form | Beispiel | Aufwand |
|---|---|---|
| Eigene Betriebszahlen | Jahre DocuWare-Partnerschaft, Anzahl betreuter Systeme, grösste Installation | sehr klein |
| Der «200 Nutzer:innen»-Satz | zurück in den DMS/ECM-Teaser | minimal |
| Anonymisierte Projektskizze | «Industriebetrieb, 180 Mitarbeitende — Rechnungseingang von 4 Tagen auf 4 Stunden» | klein |
| Selbstanwendung als Beleg | Odoo, das LLM-Gateway, das eigene DMS — alles läuft produktiv im Haus und ist ehrlicher als jede Referenz | klein, Material existiert |
| Kundenzitat mit Namen | AGB Ziffer 6 erlaubt die Referenznennung bereits standardmässig, sofern nicht widersprochen | mittel — Freigabegespräch |

**Empfohlener erster Schritt:** die Selbstanwendung. Sie ist bereits belegt (KI-Seiten, LLM-Artikel, Odoo im Expertise-Text), braucht keine Freigabe von Dritten und passt exakt zum bestehenden Ton.

---

## 2. Call-to-Actions — schrittweise Öffnung

**Ist-Zustand.** Auf keiner Seite existiert ein Button, ein hervorgehobener Handlungsaufruf oder ein Abschlusspunkt. «Kontakt» ist ein Textlink in der Navigation, optisch gleichwertig zu «Medien» und «Netzwerk».

Die Komponente ist fertig gebaut und ungenutzt: `resources/views/components/band/cta-band.blade.php` (mit Brand-Glow und Slot für Buttons), verwendet nur in `app/products/show.blade.php` (Seite stillgelegt) und unter `/demo`. Auch ein Text liegt bereit — `lang/de_CH/components.php:50`, `contact_cta`: «Interessiert?» / «Lassen Sie uns sprechen.»

**Hinweis zum vorhandenen Text:** Er siezt. Die ganze Website duzt. Vor jeder Verwendung umschreiben.

**Vorgeschlagene Reihenfolge der Öffnung** — jeweils ein Schritt, dann beobachten:

1. **Unter dem LLM-Artikel** — bereits beschlossen, Umsetzung in [`user-journey.md`](user-journey.md). Der natürlichste Ort: Wer 7 Minuten über lokale LLMs gelesen hat, hat sich selbst qualifiziert.
2. **Unter den KI-Seiten** — sobald der Abschnitt «Was das für dich bedeutet» steht (siehe Punkt 4).
3. **Unter den Expertise-Detailseiten** — sobald diese existieren (Ausbau ist für die kommende Woche eingeplant).
4. **Auf der Startseite** — zuletzt, und erst wenn oberhalb davon ein Beleg steht. Ein CTA auf einer Seite ohne Beleg wirkt aufdringlich; mit Beleg wirkt er wie ein Angebot.

**Nicht empfohlen:** «Kontakt» in der Hauptnavigation als Button hervorheben. Das ist die klassische Empfehlung, würde aber die ruhige, gleichwertige Navigationsleiste brechen, die zu den Stärken der Seite gehört.

---

## 3. Die Startseite neu positionieren

**Ist-Zustand.** Struktur: H1 «Innovative Ideen zum Leben erwecken» → «Wer wir sind» → «Was wir machen» → «Wie wir arbeiten» → Neuigkeiten → Mehr entdecken.

Oberhalb der Falz (1440 × 900) sieht der Besucher die H1, den Absatz «Wer wir sind» und den Anfang von «Was wir machen». Kein Bild, kein Beleg, kein Hinweis darauf, für wen das Angebot gedacht ist.

**Die inhaltliche Beobachtung.** Alle sieben Hauptseiten sprechen aus der Anbieterperspektive — «wer wir sind», «was wir machen», «wie wir arbeiten». Kein Kapitel benennt den Kunden, sein Problem, seine Branche oder seine Grösse. Das ist konsistent und ehrlich, kostet aber genau die Besucher, die sich selbst wiedererkennen müssten.

Die H1 ist zudem austauschbar — sie könnte über jeder Agentur, jedem Startup und jeder Beratung stehen. Die tatsächliche Differenzierung (Bindeglied Business/Technik, Ehrlichkeit bis zur Selbstabsage, offene Technologien, eigene KI-Infrastruktur) steht erst im Fliesstext darunter. Und «Wie wir arbeiten» — inhaltlich der stärkste Text der ganzen Website — steht an dritter Stelle, wo ihn die wenigsten erreichen.

**Bausteine, wenn die Zeit reif ist:**

- H1 auf das eigentliche Versprechen schärfen. Material ist da: «Wir hören zu, bevor wir bauen» — oder die Selbstabsage-Haltung nach vorn ziehen.
- Lead-Absatz unter der H1 mit Zielgruppe: «für Schweizer KMU und Konzerne, von der Konzeption bis zum Betrieb».
- Beleg-Band oberhalb der Falz (setzt Punkt 1 voraus).
- Eine visuelle Ankerfläche. Die Illustrationen auf `/dienstleistungen` und in den News sind eigenständig und gut — ausgerechnet die wichtigste Seite hat keine. Die einzigen Bilder der Startseite sind heute zwei Autoren-Avatare und zwei News-Thumbnails.

**Abhängigkeit:** Punkt 1 zuerst. Eine geschärfte H1 über einer belegfreien Seite verschiebt das Problem nur nach oben.

---

## 4. Den KI-Bereich auf den Kunden wenden

**Ist-Zustand.** `/ki` → `/ki/llm` → `/ki/llm-analytics`: welche Modelle laufen, wie viel RAM sie brauchen, unter welcher Lizenz sie stehen, plus eine filterbare Token-Statistik nach Jahr, Monat und Modell.

Als Transparenz-Statement ist das stark und echt differenzierend — kaum ein Schweizer KMU-Dienstleister legt seinen eigenen KI-Verbrauch offen. Der Satz auf `/ki` («Kundendaten verlassen unsere Infrastruktur nicht») ist das beste Verkaufsargument der ganzen Website.

**Was fehlt.** Das Argument wird nie auf den Kunden gewendet. Es gibt keinen Satz, der «und das heisst für dich: …» formuliert.

**Langfrist-Baustein:** ein Abschnitt «Was das für dich bedeutet» auf `/ki` — Datenhoheit bei der Dokumentenverarbeitung, kalkulierbare Kosten, keine Abhängigkeit von Anbieter-Preisänderungen. Das ist Textarbeit mit Positionierungscharakter, deshalb hier und nicht in der Basis.

*Die reine Rückverlinkung innerhalb des KI-Bereichs ist dagegen eine Basis-Korrektur und steht in [`user-journey.md`](user-journey.md).*

---

## 5. Angebotstiefe: stillgelegte Bereiche

Vier Bereiche sind per Redirect auf die Startseite stillgelegt, ein fünfter ist unverlinkt. **Die Stilllegungen sind sauber gemacht** — kein einziger interner Link zeigt auf eine Redirect-Route oder einen 404, und die Begründung steht als Kommentar im Controller (`OpenSourceIndexController`: «Disabled until the listing actually has entries»). Das soll so bleiben.

| Bereich | Zustand | Bemerkung |
|---|---|---|
| **Co-Working / Arbeitsplätze** | 302 → Start | Die **einzige Seite mit einem konkreten Angebot inkl. Preis** — CHF 750/Monat, Zusatzleistungen, Mietkonditionen. Vollständig in `CoWorkingIndexController` auskommentiert, Texte in `lang/de_CH.json`. Kürzester Weg zu einer messbaren Conversion, weil das Angebot buchbar und der Entscheid klein ist. |
| **Produkte** | 302 → Start | `database/files/products/` enthält nur `flows.md`. Produktseiten sind der grösste Positionierungsschritt: Sie verschieben codebar von «Dienstleister» zu «Anbieter». |
| **Technologien** | 302 → Start | `laravel-framework.md` vorhanden. Eher SEO- als Journey-Thema. |
| **Open-Source-Beiträge** | 302 → Start | Bewusst aus, bis `sync:repositories` läuft und Einträge existieren. |
| **BaselHack-Detailseite** | unverlinkt, `/netzwerk/{slug}` → 404 | `resources/views/app/network/pages/baselhack.blade.php` fertig gebaut. Kleiner Baustein für die Community-Geschichte. |

**Empfohlene Reihenfolge:** Co-Working zuerst (fertig, konkret, kleiner Entscheid), Produkte zuletzt (grösster Positionierungsschritt, braucht Belege und Angebotstiefe darunter).

---

## 6. Redaktionelle Substanz aufbauen

**Ist-Zustand.** 4 Artikel, davon 3 DocuWare-Release-Notes in nahezu identischer Bauart («DocuWare 7.12 / 7.13 / 7.14 ist da», je 2 Min.). Der Themenfilter bietet drei Kategorien, von denen zwei (KI, Open Source) durch denselben einen Artikel belegt sind.

Das Rubrik-Versprechen lautet: «Einblicke aus unserem Alltag: was wir bauen, was wir dabei lernen und was sonst gerade bei codebar passiert.» Geliefert werden zu 75 % Herstellermeldungen.

**Kein Strukturproblem, ein Bestandsproblem.** Der LLM-Artikel zeigt exakt die richtige Richtung: eigenes Problem, eigene Lösung, ehrlich erzählt, 7 Minuten Substanz. Zwei bis drei Artikel dieser Machart pro Jahr tragen die Rubrik — und liefern nebenbei das Material für Punkt 1 (Belege) und Punkt 2 (CTA-Anlässe).

*Die kurzfristige Filter-Korrektur steht in [`user-journey.md`](user-journey.md).*

---

## 7. Abhängigkeiten auf einen Blick

```
Belege (1)  ──┬──→  Startseite neu positionieren (3)
              │
              ├──→  CTA Startseite (2, Schritt 4)
              │
Redaktion (6) ┴──→  CTA LLM-Artikel (2, Schritt 1)   ← läuft bereits

KI auf Kunden wenden (4)  ──→  CTA KI-Seiten (2, Schritt 2)

Expertise-Ausbau (nächste Woche)  ──→  CTA Expertise (2, Schritt 3)

Co-Working (5)  ──→  eigenständig, keine Abhängigkeit
```

**Was ohne jede Vorbedingung sofort gehen könnte:** der «über 200 Nutzer:innen»-Satz zurück in den DMS/ECM-Teaser, und die Reaktivierung von Co-Working.
