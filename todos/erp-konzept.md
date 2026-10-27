# ERP-Bereich (Odoo im Zentrum) — Konzept

**Stand:** 2026-10-05, Branch `feature-updates`
**Ziel:** Einen ERP-Bereich aufbauen (heute mit Odoo als einzigem System), darin **Lösungen verkaufen** (Projektmanagement, Aufgaben, Zeiterfassung, …) und unsere Odoo-Pakete einzeln zeigen. Dazu zeigen, wie unsere Bausteine zusammenspielen: Odoo als ERP in der Mitte, daneben DocuWare, CloudDocs, Flows, die MCP Bridge und Pocket.

---

## 1. Ausgangslage

Odoo kommt auf der Website heute nur an drei Stellen vor:

| Ort | Inhalt |
|---|---|
| `database/files/services/*/open-source-erp.md` | Eine Zeile auf `/dienstleistungen`. Kernaussage: «Wir setzen Odoo selbst ein, Angebot Schritt für Schritt, ohne Lizenz-Lock-in». Konkret angeboten werden nur Projektmanagement und Zeiterfassung. `services.show` leitet um, die Detailseite gibt es also nicht. |
| `database/files/networks/odoo.yaml` | Odoo als Learning Partner auf `/netzwerk` |
| `config/company.php` → `knows_about` | Odoo und Open-Source-ERP für schema.org |

**Nirgends erwähnt:** CloudDocs, Pocket, die MCP Bridge, unsere Odoo-Module und das Zusammenspiel der Bereiche. Flows ist das einzige Produkt (`products/*/flows.md`), aber der ganze Bereich Produkte leitet auf die Startseite um. Die DMS/ECM-Seiten (`ServicesDmsEcmIndexController`, `ServicesDocuwareExportIndexController`) sind fertig gebaut, ihre Routen aber auskommentiert.

**Was es schon gibt** (Stand der Repos `codebar-ag/*`):

| Baustein | Repo | Was es tut | Lizenz / Vertrieb |
|---|---|---|---|
| **DocuWare Authentication** | `odoo.docuware-authentication` | Basis aller DocuWare-Konnektoren: Verbindung, OAuth2, REST-Client, unveränderliches Audit-Log | LGPL-3, gratis auf apps.odoo.com |
| **DocuWare Master Data** | `odoo.docuware-masterdata` | Schreibt Odoo-Stammdaten (Kontakte, Kontenplan, Steuern, Journale, jedes Modell) in DocuWare-Datensatz-Schränke. Mapping per UI, ohne Code. | LGPL-3, 960 EUR auf apps.odoo.com |
| **DocuWare Accounting** | `odoo.docuware-accounting` | Holt freigegebene Belege aus DocuWare und legt sie als Lieferantenrechnung/Gutschrift in Odoo an, inkl. Positionen, Dublettenschutz, Audit-Log und Rückschreiben des Status nach DocuWare | LGPL-3, 1440 EUR auf apps.odoo.com |
| **MCP Bridge** | `odoo.mcp-bridge` | Macht Odoo zum MCP-Server. Claude, ChatGPT, Cursor oder VS Code arbeiten mit Projekten, Aufgaben, Zeiten, Kontakten, Mitarbeitenden, Produkten, Preislisten, Offerten und Abos. **Jeder Aufruf läuft als die angemeldete Person**, Odoo-Rechte gelten unverändert. | LGPL-3, gratis auf apps.odoo.com (7 Addons) |
| **Pocket** | `odoo.pocket` + `odoo.pocket.mobile` | Native Mobile-App (NativePHP) für die Zeiterfassung in Odoo. Onboarding per Einladungsmail mit Deeplink: ein Tipp, kein Server, keine Datenbank, kein API-Key. | Addon gratis auf apps.odoo.com, App MIT |
| **Default** | `odoo.default`, `odoo.default.hr-timesheet` | Die Grundausstattung jeder codebar-Odoo-Instanz (Mail, 2FA, KPI-Digest, Buchhaltung, Schweiz). Konfiguriert statt geforkt. Dazu die Kundenspalte in der Zeiterfassung. | LGPL-3 |
| **Sales Extension** | `odoo.sales-extension` | Offerten und Rechnungen wie ein klassisches Schweizer Geschäftsdokument: feste Positionsnummern, Rabatt pro Zeile mit korrekter MWST, manuelle Seitenumbrüche | LGPL-3 |
| **laravel-odoo** | `laravel-odoo` (public, Packagist) | Typisierter Odoo-Connector für eigene Laravel-Apps | MIT |
| **CloudDocs Portal** | `portal.clouddocs.ch` | Kundenportal für Enterprise-Kunden auf DocuWare-Basis | Produkt / SaaS |
| **Flows** | `flows.codebar.ch` | KI-Workflows für Dokumentenprozesse, MCP-Tool-Anbindung, Azure-Betrieb | Produkt, Text existiert bereits |

Wir haben also deutlich mehr gebaut, als die Website zeigt. Das ist die Lücke, die dieses Konzept schliesst.

---

## 2. Die Kernaussage

> **Odoo führt die Geschäftsdaten. Alles andere dockt daran an, und nichts wird doppelt gepflegt.**

Das Bild dazu ist eine Nabe mit Speichen, nicht eine Produktliste:

```
                     ┌──────────────────────┐
                     │   CloudDocs Portal   │   Kund:innen sehen ihre
                     │   (Enterprise)       │   Belege und Dokumente
                     └──────────┬───────────┘
                                │ Sync
┌──────────────┐     ┌──────────┴───────────┐     ┌──────────────────┐
│  DocuWare    │◄───►│                      │◄───►│  Flows           │
│  DMS         │     │      Odoo ERP        │     │  KI-Workflows    │
│              │     │                      │     │  (über MCP)      │
└──────────────┘     └──────────┬───────────┘     └──────────────────┘
 Stammdaten ↗ Odoo→DW            │
 Belege     ↙ DW→Odoo  ┌─────────┴──────────┐
                       │ MCP Bridge · Pocket │   Mensch arbeitet per
                       │ (Assistent, Mobile) │   Chat oder Handy
                       └─────────────────────┘
```

Jede Speiche wird mit **dem Datenfluss** beschriftet, nicht mit dem Produktnamen:

| Speiche | Richtung | Was fliesst | Baustein |
|---|---|---|---|
| Odoo → DocuWare | Stammdaten | Kontakte, Konten, Steuern, Journale | DocuWare Master Data |
| DocuWare → Odoo | Belege | Freigegebene Kreditorenrechnungen, Gutschriften; Status zurück nach DocuWare | DocuWare Accounting |
| Odoo ↔ CloudDocs | Kundensicht | Kund:innen sehen Belege und Dokumente, ohne Zugang zu Odoo oder DocuWare | CloudDocs Portal |
| Dokument → Flows → Odoo/DocuWare | Vorbereitung | KI-Agenten extrahieren und prüfen gegen Odoo-Daten, Mensch gibt frei | Flows + MCP |
| Mensch ↔ Odoo | Bedienung | Zeiten, Aufgaben, Offerten per Chat-Assistent oder Handy | MCP Bridge, Pocket |

**Durchgehende Geschichte für die Seite** (eine Rechnung von Anfang bis Ende):
1. Ein Lieferant ist in Odoo erfasst → steht dank Master Data auch in DocuWare zur Verfügung.
2. Seine Rechnung kommt ins DocuWare. Flows bereitet sie vor: Felder auslesen, gegen den Lieferanten in Odoo prüfen.
3. Eine Person prüft und gibt frei.
4. DocuWare Accounting legt die Kreditorenrechnung in Odoo an und schreibt den Status zurück.
5. Der Kunde sieht im CloudDocs-Portal, dass der Beleg verarbeitet ist.

Das zeigt das Zusammenspiel in einem Satz pro Schritt, ohne Architekturvokabular.

**Messaging-Regeln, die gelten:**
- KI **unterstützt** und bereitet vor, sie übernimmt nichts. Schritt 3 (Mensch gibt frei) ist Pflicht in jeder Darstellung.
- **Selbstanwendung als Beleg:** Wir betreiben Odoo, Pocket und die MCP Bridge selbst. Das ist laut `reports/longterm.md` der ehrlichste Beleg, den wir haben, und braucht keine Kundenfreigabe.
- Duzen, Schweizer Geschäftsdeutsch, keine Buzzwords. Kein Kontaktformular.

---

## 3. Informationsarchitektur

**Entscheid (2026-10-05): Oberbereich «ERP», darunter das System «Odoo», darunter je Paket eine eigene Seite.** Später können weitere ERP-Systeme dazukommen, ohne die Struktur umzubauen.

```
/erp                                   ERP-Bereich: Kernaussage, Nabe-Diagramm (ERP in der Mitte),
                                       Rechnungs-Geschichte, unterstützte Systeme (heute: Odoo)
/erp/odoo                              System-Seite: Odoo bei uns, im Zentrum die Lösungen als Karten
                                       (statt einer Feature-Liste), darunter die Pakete
/erp/odoo/loesungen/{loesung}          Lösungs-Seite, z. B. /erp/odoo/loesungen/zeiterfassung
                                       (EN /erp/odoo/solutions/time-tracking)
/erp/odoo/docuware                     Paket-Seite DocuWare: Authentication, Accounting, Master Data
/erp/odoo/mcp-bridge                   Paket-Seite MCP Bridge
/erp/odoo/pocket                       Paket-Seite Pocket
/erp/{system}/{paket}                  Muster für spätere Systeme
```

`/erp`, Systeme und Pakete haben in DE und EN dieselben Pfade. Lösungen haben sprachabhängige Slugs (`/erp/odoo/loesungen/zeiterfassung` · `/erp/odoo/solutions/time-tracking`). Das Präfix `loesungen`/`solutions` verhindert, dass ein Lösungs-Slug mit einem Paket-Slug unter `/erp/odoo/{paket}` kollidiert. Routen: `erp.index`, `erp.solutions.show`, `erp.systems.show`, `erp.packages.show`, je mit Präfix `de-ch.` / `en-ch.`.

**Drei Blickwinkel auf dieselbe Sache:**

| Ebene | Frage der Besucher:in | Beispiel |
|---|---|---|
| **System** | «Mit welchem ERP?» | Odoo |
| **Lösung** | «Habe ich dieses Problem, und löst ihr es mit Odoo?» | Zeiterfassung mit Odoo |
| **Paket** | «Was habt ihr dafür selbst gebaut?» | Pocket, MCP Bridge |

Verkauft wird über die **Lösung**, und zwar **pro System**. Die Pakete sind der Beleg dahinter. Auf `/erp/odoo` stehen deshalb die Lösungen im Zentrum, nicht eine Liste von Odoo-Funktionen.

**Paket ≠ Addon.** Ein Paket ist, was jemand kauft oder installiert und was eine eigene Seite verdient. Auf apps.odoo.com sind heute **11 Addons** gelistet. **Entscheid (2026-10-05): genau 3 Pakete**, die DocuWare-Module laufen unter einem gemeinsamen Paket «DocuWare»:

| Paket | Addons auf apps.odoo.com | Preis |
|---|---|---|
| **DocuWare** | `docuware_authentication` (Basis, gratis) · `docuware_accounting` (1440 EUR) · `docuware_masterdata` (960 EUR) | je Addon |
| **MCP Bridge** | `mcp_bridge`, `mcp_bridge_contacts`, `mcp_bridge_hr`, `mcp_bridge_product`, `mcp_bridge_project`, `mcp_bridge_sale`, `mcp_bridge_timesheet` | gratis |
| **Pocket** | `pocket` (Addon; dazu die Mobile-App) | gratis |

Auf der DocuWare-Seite stehen die drei Addons als eigene Abschnitte untereinander: zuerst Authentication als Basis, dann Accounting (Belege DocuWare → Odoo) und Master Data (Stammdaten Odoo → DocuWare), jeweils mit Ablauf, Preis und Store-Link. Ein Abschnitt pro Addon hat einen Anker (`/erp/odoo/docuware#accounting`), damit das Diagramm direkt dorthin springen kann.

Default, Sales Extension und Default Timesheets erscheinen nicht als Paket.

Quelle: <https://apps.odoo.com/apps/modules/browse?search=codebar+solutions+ag> (Stand 2026-10-05, alle Odoo 19.0).

**Wo CloudDocs und Flows hingehören:** Sie sind keine ERP-Pakete. Auf `/erp` erscheinen sie im Diagramm als Speichen mit Verweis nach aussen (CloudDocs) bzw. auf die Flows-Produktseite, sobald diese wieder aktiv ist.

**Navigation:** «ERP» als Menüpunkt direkt nach «Dienstleistungen» in `PageNavigation::pages()`. `chain()`: `services.index → erp.index`, `erp.index → contact.index`, `erp.systems.show → erp.index`, `erp.packages.show → erp.systems.show` (zurück zum eigenen System). Die Dienstleistungszeile `open-source-erp` verlinkt auf `/erp/odoo`. Breadcrumb auf Paket-Seiten: ERP › Odoo › Paket.

**DMS/ECM-Seiten:** Die fertigen, aber auskommentierten Seiten (`/dienstleistungen/dms-ecm`) gehören in diesen Zug. Das DocuWare-Paket verlinkt dorthin für alle, die DocuWare ohne Odoo nutzen. Die Routen werden zusammen mit dem ERP-Bereich wieder aktiviert, sonst fehlt der Rückweg.


---

## 3a. Lösungen

**Entscheid (2026-10-05):** Wir verkaufen Lösungen für die Bereiche, die wir heute mit Odoo abdecken. Jede Lösung bekommt eine eigene Seite, **pro ERP-System** (Entscheid 2026-10-05: «Lösungs-Seiten pro Odoo statt Features zeigen»). Die Lösung heisst nach dem Bedarf (Zeiterfassung, nicht «Odoo Timesheets»), lebt aber unter dem System und zeigt konkret, wie wir sie mit Odoo umsetzen. Kommt ein zweites ERP dazu, bekommt es eigene Lösungs-Seiten mit eigenem Inhalt; die Schlüssel (`time-tracking`) dürfen sich wiederholen.

**Was wir heute abdecken.** Grundlage ist, was in unserer eigenen Odoo-Instanz produktiv läuft. Die MCP Bridge listet diese Bereiche: Projekte, Aufgaben, Zeiterfassung, Kontakte, Mitarbeitende, Produkte, Preislisten, Verkauf, Abos. Dazu kommen die Buchhaltung (`codebar_default_account`, `l10n_ch`) und die DocuWare-Pakete.

| # | Lösung | Was sie löst | Odoo-Apps | Unsere Pakete / Bausteine | Welle |
|---|---|---|---|---|---|
| 1 | **Projektmanagement** | Projekte mit Phasen, Vorlagen zum Duplizieren, Übersicht über Budget und Stand | Project | MCP Bridge | 1 |
| 2 | **Aufgabenverwaltung** | Aufgaben, Stufen, Zuständigkeit, Notizen. Wer macht was bis wann. | Project | MCP Bridge | 1 |
| 3 | **Zeiterfassung** | Zeit dort erfassen, wo sie anfällt: Timer, Stundenzettel, mobil. Mit Kundenbezug für die Verrechnung. | Timesheets | **Pocket**, MCP Bridge, Default Timesheets | 1 |
| 4 | **Offerten & Verkauf** | Offerte → Auftrag → Rechnung, wie ein Schweizer Geschäftsdokument: Positionsnummern, Rabatte pro Zeile, Seitenumbrüche | Sales | MCP Bridge, Sales Extension | 2 |
| 5 | **Abos & wiederkehrende Verrechnung** | Wartungsverträge, Lizenzen, Abos: verlängern, pausieren, erweitern, verrechnen | Subscriptions (Enterprise) | MCP Bridge | 2 |
| 6 | **Produkte & Preislisten** | Ein Katalog, Kundenpreise pro Preisliste | Products, Pricelists | MCP Bridge | 2 |
| 7 | **Kontakte & Kunden** | Eine Adressdatenbank für alle Systeme, ohne Dubletten | Contacts | DocuWare (Master Data), MCP Bridge | 2 |
| 8 | **Kreditoren & Belegverarbeitung** | Eingangsrechnungen aus dem DMS, geprüft und gebucht, Status zurück ins Archiv | Accounting | **DocuWare** (Accounting), Flows | 2 |
| 9 | **Mitarbeitende** | Personalverzeichnis, Abteilungen | Employees | MCP Bridge | 3 / evtl. nur Erwähnung |

**Welle 1** sind die drei vom User genannten Bereiche. Sie hängen eng zusammen (Projekt → Aufgabe → Zeit) und sind alle drei bei uns selbst im Einsatz. Ihre Seiten verlinken sich gegenseitig als Kette.

**Aufbau einer Lösungs-Seite** (gemeinsame Vorlage, Abschnitte entfallen wenn leer):
1. Breadcrumb ERP › Odoo › Zeiterfassung. H1 und ein Satz, der das Problem benennt, nicht das Produkt.
2. **Alltag ohne Lösung:** 3 kurze Punkte, an denen sich Leser:innen wiedererkennen («Stunden werden am Freitag aus dem Gedächtnis nachgetragen»).
3. **So sieht es aus:** Ablauf in 3–5 Schritten, mit Screenshots aus unserer eigenen Instanz (Testdaten).
4. **Was wir einrichten:** konkreter Umfang der Einführung (z. B. Projektvorlagen, Stufen, Rechte, Import bestehender Daten, Schulung).
5. **Mit unseren Paketen:** Karten der passenden Pakete mit Link (Zeiterfassung → Pocket, MCP Bridge).
6. **Wir nutzen es selbst:** ein, zwei Sätze, wie wir es intern einsetzen.
7. **Passt dazu:** benachbarte Lösungen (Projektmanagement ↔ Aufgaben ↔ Zeiterfassung ↔ Offerten).
9. Ansprechperson (Telefon/Mail), kein Formular.

**Messaging:** Bei KI-Bezug (MCP Bridge: «Buche 2 h auf Projekt X» im Chat) gilt: der Assistent **unterstützt** beim Erfassen, die Person bleibt verantwortlich. Keine Versprechen wie «automatische Zeiterfassung».

---

## 4. Datenmodell

**Kein Admin-Panel**, gleiches Prinzip wie News und Produkte: Dateien unter `database/files/`, Import per Command (`erp:import`).

```
database/files/erp/
  systems/
    de_CH/odoo.md            Front Matter + Text der System-Seite
    en_CH/odoo.md
  packages/
    odoo/
      de_CH/mcp-bridge.md    Front Matter + Text der Paket-Seite
      en_CH/mcp-bridge.md
      de_CH/docuware.md
      en_CH/docuware.md
      de_CH/pocket.md
      …
  solutions/
    odoo/
      de_CH/zeiterfassung.md   Front Matter + Text der Lösungs-Seite
      en_CH/time-tracking.md   (gleicher key: time-tracking)
      …
```

Drei Modelle, eine Zeile pro Locale (wie `products`, `networks`):

- **`ErpSystem`**: `key`, `locale`, `slug`, `name`, `teaser`, `body`, `logo`, `order`, `published`.
- **`ErpSolution`**: `key`, `erp_system_key`, `locale`, `slug`, `name`, `headline`, `teaser`, `body`, `pains` (Liste), `steps` (Liste mit Bild), `scope` (Liste «Was wir einrichten»), `in_house` (Text), `apps` (z. B. `[project, hr_timesheet]`), `packages` (Paket-Keys), `related` (Lösungs-Keys), `wave`, `order`, `published`.
- **`ErpPackage`**: `key`, `erp_system_key`, `locale`, `slug`, `name`, `headline`, `teaser`, `body`, `group` (docuware · assistant · mobile · base · sales), `versions` (z. B. `[19]`), `license`, `price_eur` (null = gratis), `store_url`, `repo_url`, `addons` (Liste: Anker, Name, technischer Name, Beschreibung, Features, Preis, Store-URL, `requires`), `depends` (Paket-Keys), `features`, `screenshots`, `faq`, `order`, `published`, `view` (optional).

Beispiel Front Matter:

```yaml
key: docuware
system: odoo
order: 1
name: DocuWare
headline: 'Odoo und DocuWare, ohne doppelte Pflege.'
teaser: 'Stammdaten von Odoo nach DocuWare, freigegebene Belege von DocuWare zurück als Buchung in Odoo.'
versions: [19]
license: LGPL-3
addons:
  - anchor: authentication
    name: DocuWare Authentication
    technical_name: docuware_authentication
    description: '…'
    price_eur: null
    store_url: 'https://apps.odoo.com/apps/modules/19.0/docuware_authentication'
  - anchor: accounting
    name: DocuWare Accounting
    technical_name: docuware_accounting
    description: '…'
    price_eur: 1440
    store_url: 'https://apps.odoo.com/apps/modules/19.0/docuware_accounting'
    requires: [authentication]
  - anchor: masterdata
    name: DocuWare Master Data
    technical_name: docuware_masterdata
    description: '…'
    price_eur: 960
    store_url: 'https://apps.odoo.com/apps/modules/19.0/docuware_masterdata'
    requires: [authentication]
features:
  - title: '…'
    description: '…'
screenshots: []   # Cloudinary-URLs
faq: []
published: true
---
## Markdown-Text der Seite …
```

**«100 % dediziert»** heisst: jedes Paket hat eine eigene Seite, eigene URL, eigene Metadaten, eigenes JSON-LD. Technisch rendert eine gemeinsame Vorlage (`app/erp/packages/show.blade.php`) die Abschnitte aus dem Front Matter. Braucht ein Paket mehr (z. B. Pocket mit App-Screens), zeigt `view: erp.packages.odoo.pocket` auf eine eigene Blade-View — gleiche Daten, volle Gestaltungsfreiheit.

Route-Binding über `HasLocalizedRouteBinding`; ein Paket wird nur innerhalb seines Systems aufgelöst (`/erp/odoo/x` findet kein Paket eines anderen Systems).

Die Beziehung Lösung ↔ Paket steht nur in der Lösung (`packages: [pocket, mcp-bridge]`). Die Paket-Seite leitet daraus ab, welche Lösungen sie unterstützt («Pocket hilft bei: Zeiterfassung»). So wird sie an einer Stelle gepflegt.

`ProductModule` und `Product` bleiben unberührt — die gehören zur Flows-Produktseite.

---

## 5. Diagramm

Das Nabe-Diagramm auf `/erp` ist das wichtigste Element und braucht eine eigene Designrunde. In der Mitte steht **«ERP»** mit dem Odoo-Logo darunter — so bleibt Platz für ein zweites System.

- **Inline-SVG** in einer Blade-Komponente `x-erp.hub`, kein JS nötig. Light only, Markenfarben aus Tailwind-Tokens.
- **Mobil:** Die Nabe wird zur vertikalen Liste (ERP oben, Speichen darunter, je mit Richtungspfeil und Datenfluss). Kein verkleinertes Diagramm.
- **Speichen verlinken auf die Paket-Seiten** (Stammdaten-Speiche → `/erp/odoo/docuware#masterdata`, Beleg-Speiche → `#accounting`, Mensch → MCP Bridge und Pocket).
- **Rechnungs-Geschichte:** 5 nummerierte Schritte, jeder mit kleinem Ausschnitt des Diagramms (aktive Speiche markiert).

Vorgehen wie bei News: **3–4 Varianten unter `/demo/erp`** (lokal, noindex), dann entscheiden.
Variantenideen: (a) klassische Nabe, (b) horizontale Schichten «Mensch / Prozesse / Daten / Archiv», (c) Rechnungsweg als Zeitleiste mit Systemen als Spalten, (d) Kartenraster mit ERP-Karte gross in der Mitte.

---

## 6. Inhalte pro Seite

**`/erp`** — 1. Kopf + Kernaussage. 2. Unterstützte Systeme (heute eine Karte: Odoo), je mit den Lösungen als kurze Linkliste darunter. 3. Diagramm. 4. «Eine Rechnung, fünf Schritte». 5. Ansprechperson (Telefon/Mail), kein Formular.

**Lösungs-Seiten** — siehe Abschnitt 3a.

**`/erp/odoo`** — 1. Kopf: Warum Odoo, kein Lizenz-Lock-in. 2. **Lösungen als Kartenraster** — das ist der Verkaufsteil und ersetzt jede Feature-Liste. Welle 1 zuerst, jede Karte: Name, Problem in einem Satz, Pakete als kleine Badges. 3. Wie wir einführen (Schritt für Schritt). 4. «Wir nutzen es selbst» mit echten Zahlen, Odoo Learning Partner. 5. **Die drei Pakete als Karten** (DocuWare · MCP Bridge · Pocket), je mit Preis/«gratis», Odoo-Version, Link zur Paket-Seite.

**Paket-Seite (Vorlage)**, in dieser Reihenfolge, Abschnitte entfallen wenn leer:
1. Breadcrumb ERP › Odoo › Paket; Name, Headline, Teaser.
2. Faktenleiste: Odoo-Version · Lizenz · Preis/gratis · «Auf apps.odoo.com» ↗ · Repo ↗ (falls öffentlich).
3. Problem und Lösung (Markdown-Body).
4. Was es konkret tut (kurz, kein Funktionskatalog; der Verkauf passiert auf den Lösungs-Seiten).
5. Screenshots.
6. Enthaltene Addons, untereinander, je mit Anker, Preis und Store-Link; `requires` als Hinweis («braucht DocuWare Authentication»).
7. Hilft bei: Lösungen, die dieses Paket nutzen (abgeleitet). Passt zusammen mit: die anderen Pakete desselben Systems.
8. FAQ (Versionspolitik: jede Odoo-Hauptversion ist ein eigenes Release).
9. Ansprechperson.

Paket-spezifische Inhalte:
- **MCP Bridge** — Was man im Chat sagen kann (3–4 echte Beispiele), Rechte bleiben die eigenen, Bereiche (Packs) mit Anzahl Tools, unterstützte Clients (Claude, ChatGPT, Cursor, VS Code).
- **DocuWare** — Einstieg: beide Richtungen in einem Bild (Stammdaten hin, Belege zurück). Darunter die drei Addons:
  - *Authentication* — Basis, gratis, kurz: Verbindung, OAuth2, Audit-Log.
  - *Accounting* — Ablauf Beleg → Prüfung → Buchung → Status zurück, Dublettenschutz.
  - *Master Data* — welche Stammdaten, Mapping per Oberfläche ohne Code.
  Verweis auf die DMS/ECM-Seiten für alle, die DocuWare ohne Odoo nutzen.
- **Pocket** — App-Screens, Onboarding per Einladungslink, eigene View.

---

## 7. SEO

- JSON-LD: `/erp` und `/erp/odoo` als `WebPage`/`CollectionPage` im bestehenden Graph; jede Paket-Seite als `SoftwareApplication` (`applicationCategory: BusinessApplication`, `operatingSystem: Odoo 19`); bei DocuWare ein `SoftwareApplication`-Knoten pro Addon mit eigenem `offers` (Preis bzw. `price: 0`) und Store-URL als `sameAs`. Pocket zusätzlich als `MobileApplication`, sobald im Store. Breadcrumb als `BreadcrumbList`.
- `config/company.php` → `knows_about` um ERP, MCP und DocuWare-Integration ergänzen.
- Seiten-Metadaten für Index und System unter `database/files/pages/erp.*.yaml`; Paket-Seiten nehmen Titel/Beschreibung aus dem Front Matter. Sitemap-Einträge, hreflang wie üblich.

---

## 8. Etappen

| # | Etappe | Inhalt | Abhängigkeit |
|---|---|---|---|
| 0 | **Design-Entscheid** | 3–4 Diagramm-Varianten unter `/demo/erp`, dazu ein Entwurf der Paket-Vorlage | — |
| 1 | **Fundament** | Modelle, Migrationen, `erp:import`, Routen, Binding, Navigation, `chain()`, Tests | — |
| 2a | **Lösungen Welle 1** | Vorlage + Projektmanagement, Aufgabenverwaltung, Zeiterfassung (DE/EN), Screenshots aus der eigenen Instanz | Etappe 1 |
| 2 | **Paket-Seiten** | Vorlage + Inhalte der 3 Pakete (DocuWare, MCP Bridge, Pocket), JSON-LD | Etappe 1 |
| 3 | **System-Seite** | `/erp/odoo` mit Paket-Karten | Etappe 2 |
| 4 | **ERP-Seite** | `/erp` mit Diagramm und Rechnungs-Geschichte; DMS/ECM-Routen reaktivieren | Etappe 0, 3 |
| 4a | **Lösungen Welle 2** | Offerten & Verkauf, Abos, Produkte & Preislisten, Kontakte, Kreditoren | Etappe 2a |
| 5 | **Verteilung** | News-Artikel (z. B. «Odoo im Chat-Assistenten»), Startseiten-Hinweis, Dienstleistungszeile verlinken | Etappe 1–4 |

Etappen 1–3 lassen sich ohne Design-Entscheid umsetzen und reichen für eine erste Veröffentlichung (dann ohne `/erp`-Diagramm: `/erp` leitet bis Etappe 4 auf `/erp/odoo` um, oder zeigt vorerst nur die System-Karten).

---

## 9. Offene Punkte

- [x] **Name des Bereichs:** «ERP», darunter «Odoo» (entschieden 2026-10-05).
- [x] **Sind die Module auf apps.odoo.com?** Ja, 11 Addons, siehe Abschnitt 3.
- [x] **Lösungen verkaufen:** ja, eigene Seiten pro System unter `/erp/odoo/loesungen/…`, auf `/erp/odoo` statt einer Feature-Liste, Welle 1 = Projektmanagement, Aufgabenverwaltung, Zeiterfassung (entschieden 2026-10-05).
- [ ] **Lösungsliste bestätigen:** Abschnitt 3a. Fehlt etwas (z. B. Buchhaltung allgemein, CRM/Leads, Lager, Helpdesk)? Mitarbeitende als eigene Seite oder nur erwähnen?
- [ ] **Projektmanagement und Aufgabenverwaltung:** zwei Seiten (wie gewünscht) oder eine? Beide laufen in der Odoo-App Project. Zwei Seiten sind nur sinnvoll, wenn sie unterschiedliche Zielgruppen ansprechen (Projektleitung vs. Team).
- [ ] **Screenshots:** aus einer Demo-Instanz mit Testdaten, nie aus der produktiven.
- [x] **Paket-Schnitt:** 3 Pakete — DocuWare (3 Addons), MCP Bridge (7 Addons), Pocket (entschieden 2026-10-05).
- [ ] **CloudDocs:** Was synchronisiert das Portal heute genau mit Odoo (Rechnungen, Dokumente, Kontakte)? Eigene Marke mit Link nach aussen oder als codebar-Baustein zeigen?
- [ ] **Preise auf der Website zeigen** (Empfehlung: ja, sie sind im Store ohnehin öffentlich) oder nur verlinken?
- [ ] **Pocket-App:** Im App Store / Play Store öffentlich?
- [ ] **Default, Sales Extension, Timesheets:** bewusst nicht als Paket gezeigt. Später eigenes Paket, oder nur im Text der Odoo-Seite erwähnen?
- [ ] **Repos öffentlich?** Alle Odoo-Module sind privat. Öffentliche Repos wären ein starker Beleg und passen zu LGPL-3.
- [ ] **Belege:** Welche Zahlen dürfen wir zeigen (Kundeninstanzen, Benutzer, verarbeitete Belege)?
- [ ] **Screenshots / Video** für MCP Bridge und Pocket — wer erstellt sie?
- [ ] **Odoo Learning Partner:** Ansprechperson (Domenik, Nachname fehlt noch, siehe Netzwerk-Konzept) auf `/erp/odoo` zeigen?

---

## 10. Prüfung

- Pest-Tests: Routen DE/EN, Navigation, `chain()`, Modul-Import, JSON-LD-Validierung, Sitemap.
- `npm run build` + `responsecache:clear` nach jeder Blade-Änderung; neue Klassenorte in Tailwind-`@source` eintragen.
- Lighthouse gegen den Build (nicht Dev-Server) via `tests/lighthouse/`.
- Diagramm und Paket-Vorlage auf 360 px Breite prüfen (headless Chrome).
- Binding-Test: Paket eines Systems ist unter einem anderen System 404.
- Lösung verweist nur auf existierende, publizierte Pakete desselben Systems (Import bricht sonst ab); Lösungs-Slug DE/EN wechselt korrekt im Sprachumschalter.
