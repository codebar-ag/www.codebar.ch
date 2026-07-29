---
key: docuware-7-14-is-here
slug: docuware-7-14-ist-da
title: DocuWare 7.14 ist da
teaser: >-
  Die letzten Einstellungen wandern in den Browser, eine neu gebaute App bringt Aufgaben aufs Telefon, und Archive ziehen zwischen Cloud-Organisationen um.
published_at: 2026-05-12
author: sebastian.buergin@codebar.ch
tags: [DMS/ECM]
---

Dieses Release räumt auf. Die letzte Desktop-Anwendung für Einstellungen verschwindet, die Mobile App wurde von Grund
auf neu gebaut, und zwei Module werden abgekündigt.

## Alles im Browser

Die DocuWare Administration wird eingestellt. Was dort noch übrig war, findet sich neu in der DocuWare-Konfiguration:

- **Dateiverbindungen** unter «Integrationen», mit Vorschau und direktem Upload der CSV-Datei. Der Umweg über FTP
  entfällt.
- **Effektive Archivrechte** in der Benutzerverwaltung – eine Ansicht darüber, was eine Person tatsächlich darf.
- **Lizenzübersicht** in den Organisationseinstellungen, mit Lizenztyp und genutzten Anwendungen pro Person.

## Die neue App

Die Mobile App wurde neu entwickelt. Sie heisst schlicht «DocuWare», die bisherige läuft als «DocuWare Classic»
weiter. Der Zuschnitt folgt dem, was unterwegs wirklich gebraucht wird:

- Dokumente suchen und direkt aus der App heraus weitergeben – der Fall für Aussendienst und Servicetechnik.
- Aufgaben nach Prozess gruppiert abarbeiten.
- Push-Benachrichtigungen bei neuen Aufgaben. Erst damit bleiben Freigaben nicht mehr tagelang liegen, weil niemand
  ins Postfach geschaut hat.
- Dateien aus anderen Apps nach DocuWare übergeben.
- Register für den Wechsel zwischen einer Aufgabe und dem zugehörigen Dokument.

## Sicherheit und Automatisierung

- **Zwei-Faktor-Authentifizierung** war bisher freiwillig. Neu lässt sie sich für die gesamte Organisation
  vorschreiben, mit Ausnahmen für einzelne Personen oder ganze Rollen.
- **Cloud-zu-Cloud-Transfer** verschiebt oder kopiert Dokumente und ganze Archive von einer DocuWare-Cloud-Organisation
  in eine andere. Gedacht für die Zusammenführung nach einer Übernahme oder die Trennung nach einer Ausgliederung.
- **Anmerkungen bleiben erhalten:** Ein Workflow kann ein Dokument samt Stempeln und Notizen als PDF an eine
  Schnittstelle übergeben. Bisher ging nur das Original oder ein PDF ohne diese Ergänzungen.
- **Zwei neue Aufrufe der Platform API** legen Benutzergruppen an und trennen geheftete Dokumente wieder auf.
- **IDP-Schlüssel** werden beim Anlegen automatisch gesetzt; das Kopieren von Hand entfällt.

## Was verschwindet

- **DocuWare Request** wird eingestellt und ist standardmässig ausgeblendet. Wer «Export als Backup (mit
  elektronischer Signatur)» in einer bestehenden Konfiguration nutzt, ist davon nicht betroffen.
- **Connect to Outlook** läuft aus, weil Microsoft das klassische Outlook bis 2029 abkündigt. Nachfolger ist das
  Add-in DocuWare for Outlook – es übernimmt neu die bestehenden Ablagekonfigurationen mitsamt Ablageort, Indexierung
  und Behandlung von Anhängen. Umbauen muss man dafür nichts.

## Weitere Informationen

Alle Neuerungen mit Screenshots und Anwendungsbeispielen finden Sie im Knowledge Center:

- [Knowledge Center – Neues in DocuWare Version 7.14](https://knowledgecenter.docuware.com/docs/de/2026-04-docuware-714)
- [Technische Release Notes 7.14](https://knowledgecenter.docuware.com/docs/de/news-714-technical-release-notes)
- [Die neue DocuWare Mobile App](https://knowledgecenter.docuware.com/docs/de/april-26-news-new-mobile-app)
