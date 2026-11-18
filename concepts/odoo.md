# Odoo-Preisseite — Hintergrund

**Stand:** 2026-11-18

Die HTML-Dateien zeigen die Seiten 1:1 so, wie sie auf der Website aussehen. Alles, was dahinter steckt, steht hier.

| Datei | Zeigt | Lokal live |
|---|---|---|
| `odoo-preise.html` | Preisseite mit Rechner | <https://web.codebar.test/erp/odoo/preise> |
| `expertise.html` | Expertise-Seite mit dem Button «Odoo Preise» | <https://web.codebar.test/dienstleistungen> |

Die HTML-Dateien sind ein Export der echten Blade-Seiten (Layout, Navigation, Footer, CSS und Rechner inklusive) und lassen sich direkt von der Festplatte öffnen. Sie liegen nicht im Repository. Erzeugen mit `concepts/export/export.sh` (die lokale Website muss laufen).

---

## 1. Entscheide

| Thema | Entscheid |
|---|---|
| Seiten | Nur die Preisseite. Keine eigene Odoo-Einstiegsseite |
| Einstieg | Button «Odoo Preise» beim Eintrag «Open Source ERP» auf der Expertise-Seite |
| URL | `/erp/odoo/preise` (DE) und `/erp/odoo/pricing` (EN). `/erp` und `/erp/odoo` leiten dorthin |
| Umfang des Rechners | Nur laufende Kosten. Die Einführung ist individuell und steht nicht im Rechner |
| Benutzer | Vollbenutzer und Light-Benutzer getrennt erfasst und getrennt ausgewiesen |
| Rabatt | Keiner. Gerechnet wird nur mit dem Listenpreis (bei Odoo «ab dem 2. Jahr») |
| Betrieb | Gerechnet wird nur Odoo.sh. On-Premises steht als Hinweis im Hosting-Block, ohne Preis |
| Tarif | Immer Custom (Odoo.sh setzt ihn voraus) |
| Hosting | Immer Shared, kein Dedicated-Server |
| Testsystem | Genau eines, immer eingerechnet, ohne Auswahl |
| Abrechnung | Jährlich oder monatlich, oben wählbar. Monatlich: Vollbenutzer CHF 56.90, Hosting ohne die 20 % Rabatt. Light-Benutzer CHF 7.90 in beiden Fällen (Odoo nennt nur diesen einen Preis) |
| Währung | CHF, gerechnet mit 1 EUR = 1 CHF. Der Kurs wird auf der Seite nicht erwähnt |
| Grösse des Hostings | Staffel nach Anzahl Benutzer (Voll und Light zusammen), bis 150, im Hintergrund. Technische Begriffe wie «Worker» stehen nicht auf der Seite |
| Speicherplatz | 5 GB pro Benutzer, eingerechnet und angezeigt, höchstens 512 GB |
| Serverstandort | Saint-Ghislain, Belgien, mit Flagge |
| Darstellung | Lizenzen und Hosting getrennt ausgewiesen, je mit Menge × Einzelpreis, pro Monat und pro Jahr |

Eingaben auf der Preisseite: Anzahl Vollbenutzer und Anzahl Light-Benutzer.

---

## 2. Preisbasis

Quellen: <https://www.odoo.com/de_DE/pricing> und <https://www.odoo.sh/pricing>, abgerufen am 2026-11-18. Odoo nennt Euro; angezeigt wird 1:1 in CHF.

**Lizenz Odoo Enterprise, Tarif Custom, pro Benutzer und Monat bei jährlicher Abrechnung**

| | Preis |
|---|---|
| Vollbenutzer | CHF 44.90 |
| Light-Benutzer | CHF 7.90 |

Odoos Rabatt im ersten Jahr (CHF 35.90) wird bewusst nicht gezeigt.

Light-Benutzer nach Odoo: wer nur Eigenes erfasst oder abruft (Abwesenheiten, Spesen, Beurteilungen, Anwesenheit, Planung, Lohnabrechnung, Kasse, Fertigung, Lager per Barcode). Zeiterfassung gehört nicht dazu, dafür braucht es einen Vollbenutzer. Odoo vergibt Light-Lizenzen nur nach Beratung.

**Odoo.sh, pro Monat**

| Posten | Listenpreis monatlich | Bei jährlicher Abrechnung (−20 %) |
|---|---|---|
| Worker | CHF 60.00 | CHF 48.00 |
| Speicher pro GB | CHF 0.20 | CHF 0.16 |
| Testsystem (Staging) | CHF 15.00 | CHF 12.00 |

**Staffel für den Betrieb** (auf der Seite «Betrieb deines Odoo»; intern die Anzahl Worker)

| Benutzer (Voll + Light) | Worker | Betrieb pro Monat | Betrieb pro Jahr |
|---|---|---|---|
| 1 bis 25 | 1 | CHF 48.00 | CHF 576.00 |
| 26 bis 50 | 2 | CHF 96.00 | CHF 1'152.00 |
| 51 bis 75 | 3 | CHF 144.00 | CHF 1'728.00 |
| 76 bis 100 | 4 | CHF 192.00 | CHF 2'304.00 |
| 101 bis 125 | 5 | CHF 240.00 | CHF 2'880.00 |
| 126 bis 150 | 6 | CHF 288.00 | CHF 3'456.00 |

Die Worker je Stufe sind ein Vorschlag nach Odoos Empfehlung «1 Worker pro 25 Personen» und noch nicht von euch bestätigt.

**Speicherplatz:** 5 GB pro Benutzer (Voll und Light), CHF 0.16 pro GB und Monat. **Testsystem:** eines, CHF 12.00 pro Monat.

---

## Serverstandort und Backups (Recherche 2026-11-18)

| Aussage | Quelle | Stand |
|---|---|---|
| Odoo.sh nutzt Rechenzentren von Google Cloud; für Europa Saint-Ghislain, Belgien | <https://www.odoo.sh/faq> | bestätigt |
| Tägliches Backup der Produktion; 14 Backups bis zu 3 Monate (7 täglich, 4 wöchentlich, 3 monatlich) | <https://www.odoo.sh/faq> | bestätigt |
| Backups auf drei Servern an unterschiedlichen Standorten | <https://www.odoo.sh/pricing> («3 backups servers in different locations») | bestätigt, ohne Ortsangabe |
| Region Europe: Produktion in Frankreich und Belgien; Backups in Frankreich, den Niederlanden und Schweden, bei Google und OVH. Mindestens drei Kopien in getrennten Rechenzentren | <https://www.odoo.com/privacy> (Abschnitt «Physical Data Location») | bestätigt, auf der Seite |
| Nur-EU-Backups gelten für neue Kunden mit Odoo 19.0 oder neuer. Andere Kunden in der Region Europe haben bis zum Abschluss der Umstellung (geplant Q4 2026) noch ein zusätzliches Backup in Kanada | <https://www.odoo.com/privacy> | bestätigt; für Bestandskunden auf älteren Versionen im Gespräch erwähnen |
| OVH und Google hosten die Backups von Odoo.sh; Google die Produktion | <https://www.odoo.com/privacy> (Subprocessors) | bestätigt |
| Die Backup-Standorte lassen sich nicht wählen oder einschränken und können sich innerhalb der Region ändern | <https://www.odoo.com/privacy> | bestätigt, auf der Seite als «legt Odoo fest» |
| Die Region lässt sich nicht wechseln; der genaue Standort innerhalb der Zone ist vertraglich nicht garantiert | <https://www.odoo.sh/faq> | bestätigt, nicht auf der Seite |
| Testsysteme werden nicht gesichert | <https://www.odoo.sh/faq> | bestätigt, nicht auf der Seite |

Auf der Seite stehen nur die bestätigten Aussagen. Odoo nennt die Länder der Backups, aber keine Städte.

---

## 3. Rechnung

```
lizenz_monat   = voll × 44.90 + light × 7.90
benutzer       = voll + light
stufe          = erste Stufe mit benutzer <= stufe.bis
betrieb_monat  = stufe.worker × 60 × 0.8
speicher_monat = min(benutzer × 5 GB, 512 GB) × 0.20 × 0.8
test_monat     = 1 × 15 × 0.8
hosting_monat  = betrieb_monat + speicher_monat + test_monat
total_jahr     = (lizenz_monat + hosting_monat) × 12
```

Gerechnet wird in Rappen als Ganzzahl, formatiert erst bei der Anzeige. Voll und Light zusammen sind auf 150 begrenzt.

**Kontrollwerte**

| Voll | Light | Speicher | Lizenzen pro Jahr | Hosting pro Jahr | Total pro Jahr |
|---|---|---|---|---|---|
| 10 | 0 | 50 GB | CHF 5'388.00 | CHF 816.00 | CHF 6'204.00 |
| 15 | 0 | 75 GB | CHF 8'082.00 | CHF 864.00 | CHF 8'946.00 |
| 15 | 12 | 135 GB | CHF 9'219.60 | CHF 1'555.20 | CHF 10'774.80 |
| 40 | 60 | 500 GB | CHF 27'240.00 | CHF 3'408.00 | CHF 30'648.00 |
| 150 | 0 | 512 GB | CHF 80'820.00 | CHF 4'583.04 | CHF 85'403.04 |

PHP und JavaScript liefern für diese Fälle dieselben Zahlen.

---

## 4. Umsetzung

| Datei | Zweck |
|---|---|
| `config/odoo-pricing.php` | Alle Zahlen: Preise jährlich und monatlich, Kurs, Staffel, Speicher pro Benutzer und Obergrenze, Abrufdatum, Quellen |
| `app/Support/OdooPricing.php` | Rechnung und CHF-Format auf dem Server. Die Seite zeigt auch ohne JavaScript das Beispiel für 10 Benutzer |
| `resources/js/odoo-pricing.js` | Alpine-Komponente `odooPricing` für den CSP-Build. Zahlen und Texte kommen aus `data-config` und `data-labels` |
| `app/Http/Controllers/Erp/ErpOdooPricingShowController.php` | Preisseite, Metadaten über `PageAction`, FAQ als JSON-LD |
| `app/Http/Controllers/Erp/ErpRedirectController.php` | `/erp` und `/erp/odoo` leiten auf die Preisseite der aktuellen Sprache |
| `resources/views/app/erp/odoo/pricing.blade.php` | Die Preisseite |
| `lang/*/components.php` → `odoo_pricing` | Alle Texte, Deutsch und Englisch |
| `database/files/pages/erp.systems.pricing.show.yaml` | Titel, Beschreibung, `robots` |
| `resources/views/app/services/index.blade.php` | Button «Odoo Preise» beim Eintrag «Open Source ERP» |
| `app/Console/Commands/CheckOdooPricesCommand.php`, `app/Support/OdooPriceSource.php` | `odoo:check-prices`: vergleicht die Config jeden Montag mit odoo.com und odoo.sh, meldet Abweichungen im Log und per Mail an `ODOO_PRICING_NOTIFY`, ändert nichts |
| `concepts/export/` | Export der Seiten als eigenständige HTML-Dateien zum Anschauen |

**Routen:** `erp.systems.pricing.show` als `/erp/odoo/preise` (DE) und `/erp/odoo/pricing` (EN).

**SEO:** Titel, Beschreibung, Canonical, hreflang für beide Sprachen und Sitemap-Eintrag wie bei allen Seiten. JSON-LD: `WebPage` und `BreadcrumbList` aus dem bestehenden Graph, dazu `FAQPage` mit den sechs Fragen. Kein `Offer`, weil es Preise von Odoo sind. Ein eigenes Vorschaubild gibt es noch nicht, es gilt das Standardbild.

**Speicher-Obergrenze:** Shared-Hosting auf Odoo.sh endet bei 512 GB. Ab 103 Benutzern rechnet der Rechner mit 512 GB und sagt das in der Speicherzeile.

**Tests:**
- `tests/Unit/Support/OdooPricingTest.php`: Rechnung, Staffelgrenzen, Grenzwerte, Speicher-Obergrenze, Kurs, Format
- `tests/Feature/Controllers/ErpOdooPricingShowControllerTest.php`: beide Sprachen, Inhalt ohne JavaScript, SEO-Tags, JSON-LD, Sitemap, Weiterleitungen, Button, Vollständigkeit der Übersetzung
- `tests/Feature/Commands/CheckOdooPricesCommandTest.php`: Preisprüfung
- `tests/js/odoo-pricing.test.js`: Komponente und ungültige Eingaben
- `tests/Fixtures/odoo-pricing.json`: 34 Fälle, die PHP und JavaScript beide erfüllen müssen

**Ohne JavaScript** zeigt die Seite das Beispiel für 10 Benutzer; Regler und Tasten reagieren dann nicht.

Das grössere ERP-Konzept (Lösungen, Pakete, Diagramm) steht in `todos/erp-konzept.md` und bleibt gültig.

---

## 5. Offene Punkte

- [x] **Speicher über 512 GB:** bei 512 GB gedeckelt, mit Hinweis in der Speicherzeile.
- [ ] **Staffel für den Betrieb** bestätigen oder durch eure Erfahrungswerte ersetzen.
- [ ] **«Support von Odoo»** in der Liste «enthalten»: stimmt das für eure Kundschaft so?
- [ ] **Light-Benutzer:** Die Beschreibung auf der Seite gegenlesen. Zählt Light für die Hosting-Stufe mit (so gerechnet) oder nur Voll?
