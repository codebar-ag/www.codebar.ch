---
key: docuware-viewer-plugin
slug: docuware-anpassen-plugin
title: Wie flexibel lässt sich DocuWare anpassen?
teaser: >-
    Ein Kunde will seine Dokumente im bisherigen System lassen und trotzdem in DocuWare damit
    arbeiten. Wir haben ein Plugin gebaut und getestet, wie weit man damit kommt.
published_at: 2026-09-23
updated_at: 2026-09-23
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/docuware-viewer-plugin.svg
hero_alt: "Ein Dokument liegt in einem anderen System; ein Plugin verbindet den DocuWare-Viewer damit und zeigt das Dokument dort an, wo sonst das DocuWare-Dokument erscheint"
thumb: images/news/docuware-viewer-plugin-card.svg
tags: [DMS/ECM]
featured: false
---

## Die Ausgangslage

DocuWare ist ein Standardprodukt. Es bietet viele Funktionen, deckt aber nicht jede Anforderung ab.
Die Anfrage eines Kunden gab uns die Gelegenheit zu testen, wie weit man mit einem Browser-Plugin
kommt.

Der Kunde hat seine Dokumente in einem anderen System, das er nicht ablösen kann und das laufend
mit neuen Dokumenten befüllt wird. Alle Dokumente nach DocuWare zu übertragen ist keine Option,
da die Dokumente nicht dupliziert werden sollen. In DocuWare sollen deshalb nur die Indexdaten
stehen, während die Dokumente an ihrem ursprünglichen Ort bleiben. Die Mitarbeitenden sollen
trotzdem in DocuWare arbeiten und dort auf alle Daten und Dokumente zugreifen können, unabhängig
davon, wo diese gespeichert sind.

## Was wir getestet haben

Wir haben eine Erweiterung konzipiert, die sich in den Dokumentviewer von DocuWare einhängt, Entwickler
nennen das «Injection». Wird ein Datensatz geöffnet, prüft das Plugin die Indexdaten. Liegt das
Dokument in DocuWare, passiert nichts. Liegt es im anderen System, holt das Plugin die Datei von
dort und zeigt sie genau an der Stelle an, wo normalerweise das DocuWare-Dokument erscheint. In
DocuWare selbst wird dabei nichts gespeichert.

In der Praxis merkt man davon nichts. Man klickt in der Ergebnisliste auf den Datensatz und das
Dokument wird angezeigt, mit Blättern, Zoomen, Suchen, Drucken und Herunterladen wie gewohnt.
Findet das Plugin keine passende Datei, verhält sich DocuWare wie immer.

## Im Video

:::video{src="https://player.vimeo.com/video/1229533037?h=a2be7c8fa4&dnt=1" title="DocuWare-Viewer mit Plugin: ein Dokument aus einem anderen System wird angezeigt"}
Ein Datensatz ohne Datei in DocuWare, das Dokument kommt aus dem anderen System.
:::

## Das Risiko

Das Plugin basiert auf dem Code von DocuWare. Ändert sich mit einem Update etwas am Webclient,
muss das Plugin angepasst werden. Man reagiert somit auf Änderungen des Herstellers, anstatt
selbst zu entscheiden. Das spricht nicht grundsätzlich gegen den Ansatz, man sollte sich dessen
aber bewusst sein, bevor eine solche Lösung produktiv geht.

## Die Alternative

Der Service könnte als eigenständige Anwendung ausserhalb von DocuWare betrieben werden, auf die
DocuWare einfach verlinkt. Seit Version 7.12 werden Links in Indexfeldern als klickbare
Hyperlinks dargestellt, ein Strg+Klick genügt. So steuert man Verhalten, Design und Logik selbst,
und ein Update von DocuWare beeinträchtigt die Funktion nicht. Diese Lösung ist weniger nahtlos,
dafür sauber getrennt.

## Wie sieht es bei euch aus?

Möchtet ihr DocuWare um Funktionen erweitern, die es standardmässig nicht bietet? Wir
unterstützen euch gerne, zum Beispiel mit einer Schnittstelle zu einem anderen System. Erzählt
uns davon – [lass uns sprechen](https://www.codebar.ch/kontakt).

## Weitere Informationen

- [Klickbare Links in Ergebnislisten](https://knowledgecenter.docuware.com/docs/de/neuheiten-712-klickbare-links-in-ergebnislisten.md)
  – DocuWare Knowledge Center, Neuheiten 7.12
- [Chrome Extensions](https://developer.chrome.com/docs/extensions?hl=de) – Dokumentation von
  Google, so entstehen Browser-Plugins
