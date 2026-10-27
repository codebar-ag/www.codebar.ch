---
key: docuware-7-15-is-here
slug: docuware-7-15-ist-da
title: DocuWare 7.15 ist da
teaser: >-
    Mit diesem Release kommt das neue DocuWare in die Cloud: ein neu gebauter Client, der
    KI-Assistent Aura und eine Dokumentenverarbeitung, die auch Handschrift liest. Klassisch und
    neu laufen eine Zeit lang parallel.
published_at: 2026-10-15
published: false
author: sebastian.buergin@codebar.ch
hero: images/news/docuware-7-15-is-here.svg
hero_alt: "Aus einem Suchdialog mit Indexfeldern wird eine Frage in natürlicher Sprache, die der Assistent mit dem passenden Dokument beantwortet"
thumb: images/news/docuware-7-15-is-here-card.svg
tags: [DMS/ECM]
---

Dieses Release ist das grösste seit Jahren. Mit 7.15 kommt das neue DocuWare in die Cloud: ein
von Grund auf neu gebauter Client, der KI-Assistent Aura und eine Dokumentenverarbeitung, die
auch Handschrift liest. Neukunden starten bereits auf 7.15, bestehende Cloud-Kunden erhalten das
Update ab Mitte Oktober gestaffelt über rund zwei Monate. Den Termin teilt DocuWare per Mail mit.
On-Premises erscheint 7.15 ebenfalls Mitte Oktober, das neue DocuWare und Aura gibt es vorerst
aber nur in der Cloud.

## Das neue DocuWare

Der Web Client wurde komplett neu entwickelt. Die Suche geht über alle Archive, auch ohne
Indexfelder, die Oberfläche ist für jede Bildschirmgrösse dieselbe, und sie erfüllt die
Richtlinien für Barrierefreiheit nach WCAG 2.2 AA. Seit dem Sommer sind unter anderem Listen,
Versionen, ein Papierkorb und das Bearbeiten von Dokumenten direkt aus einer Workflow-Aufgabe
dazugekommen. Die Mobile App aus 7.14 ist die Begleit-App dazu.

Wichtig für den Übergang: Klassisches und neues DocuWare laufen parallel, mit denselben Daten
und Einstellungen. Ein Stempel aus dem klassischen Viewer ist auch im neuen sichtbar. Jede Person
wechselt selbst, wenn es passt. Eine Migration braucht es nicht, und der klassische Client bleibt
mindestens ein weiteres Jahr verfügbar.

## Aura: Fragen statt Suchbegriffe

Statt einen Suchdialog mit Indexfeldern auszufüllen, stellt man eine Frage in natürlicher
Sprache: «Wo ist der Beleg vom 27. Februar?» Aura antwortet mit dem passenden Dokument, fasst
Dokumente zusammen, vergleicht zwei Verträge miteinander, zieht Daten heraus oder entwirft eine
Antwort-Mail. Die Antworten enthalten Quellenangaben mit Seitenzahl, und frühere Unterhaltungen
lassen sich wieder aufnehmen.

Aura sieht nur, was die Person gemäss ihren Archivrechten sehen darf (siehe «Vor dem Update»).
Die KI läuft in der Umgebung von DocuWare auf Servern in Deutschland, nicht bei einem externen
KI-Dienst. Über die API lässt sich Aura auch aus anderen Anwendungen ansprechen.

## Was Aura kostet

Aura wird nach Nutzung abgerechnet, in sogenannten Credits. Eine aufwendige Frage über mehrere
Archive mit langen Dokumenten braucht rund 30'000 Credits, eine einfache Frage deutlich weniger.
Cloud-Kunden erhalten ein Gratiskontingent, um Aura mit den eigenen Daten auszuprobieren; die
Anzahl Dokumente ist dabei begrenzt. Wer Aura im Alltag nutzt, kauft Credits dazu, mit tieferem
Preis pro Credit bei grösserem Volumen. Die Administration kann Limits pro Person und Gruppe
festlegen.

## Dokumentenverarbeitung

Die Texterkennung liest neu auch Handschrift. Welche Daten aus einem Dokument gebraucht werden,
beschreibt die Administration in natürlicher Sprache, ein Modelltraining entfällt. Dazu kommen
automatische Klassifizierung, das Trennen von Dokumentstapeln und die Verarbeitung im
Hintergrund. Mit «Master Data Matching» werden ausgelesene Werte gegen Stammdaten aus dem ERP
geprüft, etwa Lieferantennummer und Adresse.

Die neue Konfiguration direkt in DocuWare ist in 7.15 als Early Access enthalten: Abläufe werden
per Drag-and-drop zusammengestellt, ohne DocuWare Workflow. In den nächsten Monaten folgen unter
anderem die Ablage aus Connect to Mail, E-Rechnungen und Tabellenfelder.

## Workflow Designer und Forms

Neue Workflows entstehen ab 7.15 ausschliesslich im webbasierten Designer. Er ordnet Aktivitäten
automatisch an, färbt sie je nach Typ ein und zeigt Benachrichtigungen schon während der
Bearbeitung in einer Vorschau. Bestehende Workflows lassen sich weiterhin in der
Desktop-Anwendung bearbeiten, mit 7.16 im Frühling 2027 verschwindet diese. Bestehende Workflows
erscheinen im Browser als «zu migrieren» und werden mit einem Klick übernommen; wer will, testet
zuerst an einer Kopie.

In DocuWare Forms können Werte aus Auswahllisten neu in Berechnungen einfliessen. So müssen
weniger Felder von Hand ausgefüllt werden.

## Vor dem Update: zwei Punkte prüfen

:::callout{type="warning" title="Zugriff über Archivrechte regeln, nicht über Dialoge"}
Die neue Suche und Aura finden Dokumente überall, wo eine Person Archivrechte hat, unabhängig von
Such- und Ergebnisdialogen. Wurde der Zugriff bisher über Dialoge eingeschränkt, sehen Personen
nach dem Update unter Umständen Dokumente, die ihnen vorher verborgen waren. Auch die Funktionen
im Kontextmenü der Ergebnisliste richten sich im neuen Client nur nach den Archivrechten. Deshalb
vor dem Update prüfen, ob die Archivrechte stimmen.
:::

:::callout{type="warning" title="Wer SSO nutzt, muss vor dem Update handeln"}
Mit 7.15 kommt ein neuer Identity Service mit neuen Adressen. Organisationen mit Single Sign-On
tragen vor ihrem Update-Termin eine zusätzliche Callback-URL in der SSO-Konfiguration ein. Ohne
diesen Eintrag ist nach dem Update keine Anmeldung per SSO mehr möglich. On-Premises ist nicht
betroffen.
:::

## Weitere Informationen

- [Das neue DocuWare](https://start.docuware.com/new-docuware) – Überblick von DocuWare
- [Knowledge Center zu 7.15](https://go.docuware.com/knowledgecenter715) – alle Neuerungen und
  Release Notes
- [Single Sign-On für DocuWare Cloud](https://start.docuware.com/blog/product-news/single-sign-on-for-docuware-cloud)
  – die Callback-URL vor dem Update
- [Anleitung zur Callback-URL](https://support.docuware.com/en-us/knowledgebase/article/KBA-38173)
  – DocuWare Support, KBA-38173
- [Umstieg auf den webbasierten Workflow Designer](https://start.docuware.com/blog/product-news/transition-to-docuwares-web-based-workflow-designer)
  – Zeitplan bis 7.16
