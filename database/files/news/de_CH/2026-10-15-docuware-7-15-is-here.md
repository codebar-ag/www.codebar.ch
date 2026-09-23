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
auch Handschrift liest. Das Update beginnt Mitte Oktober und wird bis Ende Jahr gestaffelt
ausgerollt. On-Premises folgt später.

## Das neue DocuWare

Der Web Client wurde komplett neu entwickelt. Die Suche geht über alle Archive, die Oberfläche
ist für jede Bildschirmgrösse dieselbe, und sie erfüllt die Richtlinien für Barrierefreiheit nach
WCAG 2.2 AA. Die Mobile App aus 7.14 ist die Begleit-App dazu.

Wichtig für den Übergang: Klassisches und neues DocuWare laufen parallel, mit denselben Daten
und Einstellungen. Jede Person wechselt selbst, wenn es passt. Eine Migration braucht es nicht.

## Aura: Fragen statt Suchbegriffe

Statt einen Suchdialog mit Indexfeldern auszufüllen, stellt man eine Frage in natürlicher
Sprache: «Wo ist der Beleg vom 27. Februar?» Aura antwortet mit dem passenden Dokument, fasst
Dokumente zusammen, vergleicht zwei Verträge miteinander, zieht Daten heraus oder entwirft eine
Antwort-Mail.

Berechtigungen gelten dabei wie überall in DocuWare: Aura zeigt nur, was die Person auch sonst
sehen darf. Die KI läuft in der Umgebung von DocuWare auf Servern in Deutschland, nicht bei
einem externen KI-Dienst. Über die API lässt sich Aura auch aus anderen Anwendungen ansprechen.
Der Assistent ist Teil des neuen DocuWare und steht allen Cloud-Kunden zur Verfügung.

## Dokumentenverarbeitung

Die Texterkennung liest neu auch Handschrift. Welche Daten aus einem Dokument gebraucht werden,
beschreibt die Administration in natürlicher Sprache, ein Modelltraining entfällt. Dazu kommen
automatische Klassifizierung, das Trennen von Dokumentstapeln und die Verarbeitung im
Hintergrund. Mit «Master Data Matching» werden ausgelesene Werte gegen Stammdaten aus dem ERP
geprüft, etwa Lieferantennummer und Adresse. Konfiguriert wird alles direkt in DocuWare, in
knapp 20 Sprachen.

## Workflow Designer nur noch im Browser

Neue Workflows entstehen ab 7.15 ausschliesslich im webbasierten Designer. Bestehende Workflows
lassen sich weiterhin in der Desktop-Anwendung bearbeiten, mit 7.16 im Frühling 2027 verschwindet
diese. Bestehende Workflows erscheinen im Browser als «zu migrieren» und werden mit einem Klick
übernommen; wer will, testet zuerst an einer Kopie.

## Vor dem Update: Single Sign-On prüfen

:::callout{type="warning" title="Wer SSO nutzt, muss vor dem Update handeln"}
Mit 7.15 kommt ein neuer Identity Service mit neuen Adressen. Organisationen mit Single Sign-On
tragen vor ihrem Update-Termin eine zusätzliche Callback-URL in der SSO-Konfiguration ein. Ohne
diesen Eintrag ist nach dem Update keine Anmeldung per SSO mehr möglich. Den Termin teilt
DocuWare per Mail mit; On-Premises ist nicht betroffen.
:::

## Weitere Informationen

- [Das neue DocuWare](https://start.docuware.com/new-docuware) – Überblick von DocuWare
- [Single Sign-On für DocuWare Cloud](https://start.docuware.com/blog/product-news/single-sign-on-for-docuware-cloud)
  – die Callback-URL vor dem Update
- [Anleitung zur Callback-URL](https://support.docuware.com/en-us/knowledgebase/article/KBA-38173)
  – DocuWare Support, KBA-38173
- [Umstieg auf den webbasierten Workflow Designer](https://start.docuware.com/blog/product-news/transition-to-docuwares-web-based-workflow-designer)
  – Zeitplan bis 7.16
