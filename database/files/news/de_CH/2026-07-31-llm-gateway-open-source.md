---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: 'KI im eigenen Serverraum: wenn zu viele Anfragen auf einmal kommen'
teaser: >-
    Unsere KI-Modelle laufen auf eigener Hardware. Kommen zu viele Anfragen gleichzeitig, scheitern
    sie an den Ressourcen. Geholfen hat uns nicht mehr Hardware, sondern eine klare Reihenfolge, in
    der sie abgearbeitet werden.
published_at: 2026-07-31
updated_at: 2026-07-31
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/llm-gateway-open-source.svg
hero_alt: >-
    Mehrere gleichzeitige Anfragen reihen sich in einer Warteschlange auf und werden der Reihe nach
    an ein Sprachmodell weitergegeben
thumb: images/news/llm-gateway-open-source-card.svg
tags: [Open Source, KI]
featured: false
---

## Ein halbes Jahr KI im Alltag

Seit rund einem halben Jahr beschäftigt uns kein Thema so stark wie KI. Wir haben viel
ausprobiert, einiges wieder verworfen und ein paar Dinge gefunden, die im Alltag wirklich helfen:
Prototypen entstehen schneller, aus Belegen werden Daten, ohne dass jemand sie abtippt, und beim
Programmieren schreiben wir weniger selbst und prüfen dafür mehr.

Eine Erfahrung zieht sich für uns durch alles: Einen erheblichen Mehrwert und echte
Effizienzgewinne bringt KI dort, wo die Prozesse schon sauber sind. Wo sie es nicht sind, wird die
Qualität auch mit KI nicht besser.

Davon zeigen wir in den nächsten Wochen mehr. Den Anfang macht ein Werkzeug, das wir für uns
selbst gebaut haben – weil unsere eigene Hardware an die Grenze kam.

## Warum die Modelle bei uns im Haus stehen

Wo immer möglich, setzen wir KI mit lokalen Modellen um: auf unserer eigenen Infrastruktur oder
auf der unserer Kunden. Kundendaten, Verträge und interne Dokumente bleiben damit dort, wo sie
ohnehin liegen. Welches Modell rechnet, ist für uns deshalb keine rein technische Frage. Es muss
zum Kunden passen und dazu, wie er mit LLMs umgehen will.

Unser Eindruck: Lokal hiess lange auch spürbar schwächer. Das hat sich für unsere Anwendungsfälle
geändert. Inzwischen reichen lokale Modelle für die meisten unserer Prozesse, und dabei gilt oft:
weniger ist mehr. Ein kleines Modell mit präziser Instruktion kommt bei uns häufig weiter als ein
grosses ohne – auch weiter als Claude oder die Modelle von OpenAI – und ist dabei deutlich
effizienter. Nicht immer, aber öfter, als wir erwartet hätten.

Dafür steht bei uns ein MacBook im Serverraum. M5 Max, 128 GB Arbeitsspeicher, darauf Ollama und
LiteLLM – und Zugriff für alle im Unternehmen.

- [codebar.ch/ki/llm](https://www.codebar.ch/ki/llm) – welche Modelle bei uns laufen
- [codebar.ch/ki/llm-analytics](https://www.codebar.ch/ki/llm-analytics) – was sie tatsächlich zu
  tun bekommen

## Wo es anfängt zu klemmen

Das eine perfekte Modell gibt es für uns nicht. In unseren Workflows läuft deshalb jeder Agent auf
dem Modell, das zu seiner Aufgabe passt: Für die Extraktion aus Belegen eignet sich ein anderes
als für eine Zusammenfassung, und für eine Klassifizierung genügt ein kleines, schnelles.

Genau daran hängt das Problem. Ein Modell muss im Arbeitsspeicher geladen sein, bevor es antworten
kann. Unsere Modelle brauchen davon zwischen wenigen und über hundert Gigabyte – alle gleichzeitig
zu laden, geht mit unseren Ressourcen nicht. Jeder Wechsel heisst also: zuerst laden, dann
rechnen. Und solange eine Anfrage rechnet, kommt keine zweite dazwischen.

Im Alltag sah das so aus: Zwei Personen und ein Agent starten kurz nacheinander etwas. Die erste
Anfrage wird gerechnet, die beiden anderen warten – und laufen irgendwann in einen Timeout. Nicht
weil die Maschine zu langsam wäre, sondern weil drei zur gleichen Zeit gefragt haben.

Eine grössere Maschine würde das entschärfen. Nur ist sie nicht überall möglich, selten die
effizienteste Antwort und verschiebt die Grenze bloss bis zum nächsten Modell. Was uns wirklich
weitergebracht hat, ist eine klare Reihenfolge, in der die Anfragen abgearbeitet werden.

## Was wir gebaut haben

Also haben wir uns ein Gateway gebaut. Es sitzt zwischen den Anfragen und dem Modell und macht
etwas sehr Einfaches: Es nimmt jede Anfrage entgegen, gibt sofort eine Nummer zurück, über die
sich die Anfrage später wiederfinden lässt, und stellt sie in eine Warteschlange. Abgearbeitet
wird sie, sobald das Modell frei ist. Die Antwort holt man anschliessend über die Nummer ab.

Zwei Dinge waren uns dabei wichtig. Erstens sollte sich am Absenden nichts ändern: Wer bisher
direkt ans Modell geschickt hat, schickt genau gleich weiter, und nur das Abholen der Antwort
kommt als zweiter Schritt dazu. Zweitens kann niemand die Warteschlange umgehen – sonst hätten wir
das Problem gleich wieder.

Mitgeliefert wird die Position in der Warteschlange und eine Schätzung der Wartezeit,
hochgerechnet aus den letzten tatsächlichen Antwortzeiten desselben Modells. Keine Zusage, aber
ein Wert, an dem sich ein Programm oder ein Mensch orientieren kann.

## Die Technik dahinter

Das von OpenAI definierte Format ist ein praktikabler Standard. Fast jede Software, die Modelle
lokal ausführt – Ollama, LiteLLM, vLLM, llama.cpp – bietet einen OpenAI-kompatiblen Endpunkt an,
auch wenn dahinter gar kein OpenAI steckt. Der übliche Aufruf `POST /v1/chat/completions` ist
synchron: Man sendet die Anfrage und hält die Verbindung offen, bis die Antwort da ist.

Derselbe Standard kennt dafür bereits die Responses API. Statt an `POST /v1/chat/completions` geht
die Anfrage an `POST /v1/responses`, zurück kommt sofort eine ID, und das Ergebnis holt man später
über `GET /v1/responses/{id}` ab – genau das asynchrone Verhalten, das wir brauchen. Nur ist sie
nicht überall verfügbar, und selbst wo sie es ist, muss der Client sie ausdrücklich verlangen. Wer
das nicht tut oder nicht kann, schickt weiter synchron.

Wir behandeln deshalb jede Anfrage als asynchronen Auftrag, auch eine synchron gestellte. Du
schickst also weiter das, was du ohnehin schicken würdest:

```bash
curl -s https://llm.async.example.com/v1/chat/completions \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3.6:35b",
       "messages":[{"role":"user","content":"Fasse dieses Protokoll in fünf Sätzen zusammen."}]}'
```

Zurück kommt aber nicht die Antwort, sondern eine ID:

```json
{
  "id": "resp_019fb90bbb8872ef9cbd309e471092c0",
  "object": "chat.completion.queued",
  "status": "queued",
  "model": "qwen3.6:35b",
  "created": 1785515981,
  "poll_url": "/v1/responses/resp_019fb90bbb8872ef9cbd309e471092c0",
  "queue": {
    "position": 1,
    "depth": 1,
    "workers": 1,
    "estimated_seconds": 18,
    "estimated_completion_at": 1785515999
  }
}
```

Die Verbindung ist damit sofort wieder frei. `estimated_seconds` ist der Wert, an dem der Client
sein Polling-Intervall ausrichten kann; abgeholt wird die Antwort über die `poll_url`, den Weg,
den die Responses API vorsieht.

Unter der Haube ist die Anwendung in Laravel geschrieben und legt jeden Auftrag in einer
Datenbank-Queue ab. Queue-Worker holen ihn dort ab und geben ihn der Reihe nach an das lokale
Modell-Interface weiter, bei uns Ollama und LiteLLM. Erreichbar ist das Gateway über einen
Cloudflare Tunnel, gestartet wird es als launchd-Agent direkt auf der Maschine, auf der auch die
Modelle rechnen.

Der Code liegt öffentlich auf GitHub, genau so, wie wir ihn einsetzen. Dazu im Repository: eine
OpenAPI-Beschreibung der Endpunkte, eine Bruno-Sammlung zum Ausprobieren und die
Betriebskonfiguration.

- [codebar-ag/llm-gateway.codebar.ai auf GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)

## Wie sieht es bei euch aus?

Wir helfen gerne weiter, wenn ihr KI auf eigener Infrastruktur betreiben wollt: bei der Auswahl
der Modelle, beim Aufbau der Workflows und bei der Reihenfolge, in der die Anfragen abgearbeitet
werden.

Am liebsten starten wir bei eurem konkreten Anwendungsfall. Erzählt uns davon –
[lass uns sprechen](https://www.codebar.ch/kontakt).

## Quellen

- [OpenAI Responses API](https://platform.openai.com/docs/api-reference/responses) – der Standard
  für asynchrone Aufrufe
- [Ollama](https://ollama.com) und [LiteLLM](https://www.litellm.ai) – die Modell-Interfaces, die
  bei uns laufen
- [Cloudflare Tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/)
  – darüber ist das Gateway erreichbar
- [Bruno](https://www.usebruno.com) – damit ist die Sammlung im Repository aufgebaut
- [Laravel](https://laravel.com) – das Framework, in dem die Anwendung geschrieben ist
