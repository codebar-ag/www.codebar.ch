---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: 'Lokale LLMs betreiben: Warteschlangen statt Timeouts'
teaser: >-
  Modelle im eigenen Haus zu betreiben, lohnt sich – bis die Anfragen steigen und die eigenen
  Ressourcen zum Engpass werden. Was wir dagegen gebaut haben.
published_at: 2026-07-31
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/llm-gateway-open-source.svg
hero_alt: Gleichzeitige Anfragen laufen neu über eine Warteschlange, die ein Gateway der Reihe nach abarbeitet
thumb: images/news/llm-gateway-open-source-card.svg
tags: [Open Source, KI]
featured: false
---

## Ein halbes Jahr KI im Alltag

Seit rund einem halben Jahr beschäftigt uns kein Thema so stark wie KI. Wir haben in dieser Zeit
viel ausprobiert, einiges wieder verworfen und ein paar Dinge gefunden, die bei uns im Alltag
tatsächlich etwas bringen. Davon möchten wir in den nächsten Wochen und Monaten etwas mehr zeigen.

Den Anfang macht ein kleines Werkzeug, das wir für uns selbst gebaut haben, weil uns etwas im Weg
stand. Dazu gleich mehr.

---

Wir, die codebar Solutions AG, entwickeln individuelle Software und betreuen
Dokumentenmanagement-Systeme für Schweizer KMU und Konzerne, von der Konzeption über die Umsetzung
bis zum Betrieb. Wo sich bei uns konkret etwas verändert hat:

- **Konzeption und Prototyping.** Prototypen entstehen schneller, und wir drehen in der gleichen
  Zeit deutlich mehr Runden: einen Stand zeigen, Feedback holen, anpassen. Entscheide fallen dadurch
  früher.
- **Individuelle Softwareentwicklung.** KI hilft dort am meisten, wo vorher schon saubere Prozesse
  standen, und übernimmt gezielt einzelne Schritte. Anforderungen klären, Ergebnisse prüfen und Code
  reviewen bleibt beim Menschen.
- **DMS und ECM.** Extraktion von Dokumentdaten und Multi-Agent-Workflows übernehmen einen
  wachsenden Teil der Arbeit. Immer weniger Dokumente müssen dafür noch von Hand angefasst werden.

In allen drei Bereichen rechnet am Ende ein Modell. Wo dieses Modell läuft, war für uns von Anfang
an eine bewusste Entscheidung.

## Kontrolle und Datenhoheit

Für uns war früh klar, dass wir eigene Modelle auf eigener Infrastruktur wollen. Sobald ein Prompt
Kundendaten, Verträge oder interne Dokumente enthält, ist die Frage nicht mehr, welches Modell am
besten antwortet, sondern wer die Anfrage zu sehen bekommt. Läuft das Modell im eigenen Haus,
erübrigt sich diese Frage.

Dazu kommt ein Punkt, der noch vor einem Jahr anders aussah: Lokale Modelle sind inzwischen so
leistungsfähig, dass sie die meisten unserer Aufgaben problemlos erledigen. Die eigentliche
Herausforderung liegt woanders, nämlich darin, die richtigen Fragen zu stellen und gute
Instruktionen zu geben. Wer Zeit in sauberes Prompt Engineering investiert, kommt oft auch mit einem
lokalen Modell zum Ziel.

Wir halten das bewusst pragmatisch. Die Modelle laufen bei uns im Haus auf einer einzelnen Maschine
mit 128 GB Unified Memory, unterbrechungsfrei abgesichert. Darauf laufen Ollama und LiteLLM, die
Modelle, die wir für unsere Aufgaben brauchen, dazu die Werkzeuge für Auswertung und Betrieb.

- [codebar.ch/ki/llm](https://www.codebar.ch/ki/llm) — welche Modelle bei uns laufen
- [codebar.ch/ki/llm-analytics](https://www.codebar.ch/ki/llm-analytics) — was sie tatsächlich zu
  tun bekommen

## Wo es anfängt zu klemmen

Wir arbeiten nicht mit dem einen perfekten Modell, sondern mit mehreren. In unseren Workflows
laufen Agenten, und jeder Agent bekommt das Modell, das zu seiner Aufgabe passt: Für die Extraktion
aus Belegen eignet sich ein anderes als für eine Zusammenfassung, und eine Klassifizierung erledigt
ein kleines, schnelles Modell, für das ein grosses überdimensioniert wäre. Dazu kommen die Anfragen
aus dem Team und aus laufenden Prozessen. Am Ende zeigt alles auf dieselbe Maschine.

Und genau da liegt die Grenze. Ein Modell muss geladen sein, bevor es antworten kann, und belegt
dabei Speicher. Unsere Modelle brauchen davon zwischen
wenigen GB und über 100 GB, das grösste allein rund 102 GB. Dazu kommt der Kontext: Je länger die
Anfrage und je mehr Dokumente mitgeschickt werden, desto mehr kommt obendrauf. Von 128 GB bleibt
damit wenig übrig — genug für genau eine Aufgabe, die gleichzeitig abgearbeitet werden kann. Und
jeder Wechsel auf ein anderes Modell bedeutet erst laden, dann rechnen. Alles darüber macht nicht
mehr, sondern nur alles langsamer.

Ohne Orchestrierung schlagen Anfragen fehl. Agenten, Prozesse und Mitarbeitende, die mit den
Modellen arbeiten, laufen in Timeouts, und niemand kann sagen, ob etwas noch rechnet oder längst
verloren ist.

## Was wir gebaut haben

Ein kurzer Blick auf die Technik, weil er den Kern erklärt: In der Praxis hat sich das von OpenAI
definierte Format als gemeinsamer Nenner durchgesetzt. Fast jede lokale Inferenz-Lösung — Ollama,
LiteLLM, vLLM, llama.cpp — bietet einen OpenAI-kompatiblen Endpunkt an, auch wenn dahinter gar kein
OpenAI steckt. Der übliche Aufruf `POST /v1/chat/completions` ist dabei synchron: Man sendet die
Anfrage und hält die Verbindung offen, bis die Antwort da ist.

Für genau dieses Problem sieht derselbe Standard bereits eine Lösung vor: die neuere Responses API.
Statt an `POST /v1/chat/completions` geht die Anfrage an `POST /v1/responses`, zurück kommt
sofort eine ID, und das Ergebnis holt man später über `GET /v1/responses/{id}` ab — also exakt das
asynchrone Verhalten, das wir brauchen. Verlassen kann man sich darauf aber nicht: Längst nicht
jedes Modell und jede Gegenstelle unterstützt sie, und selbst wo sie vorhanden ist, muss die
aufrufende Anwendung sie ausdrücklich verlangen. Wer das nicht tut oder nicht kann, schickt weiter
synchron — und steht wieder in der Warteschleife.

Genau diese Reihenfolge haben wir umgedreht. Nicht das aufrufende Werkzeug entscheidet, ob
asynchron gearbeitet wird, sondern die Warteschlange: Unsere Anwendung steht zwischen den
aufrufenden Werkzeugen und dem lokalen Modell-Interface und wandelt jede Anfrage in einen
asynchronen Auftrag um, auch eine synchron gemeinte. Kein Werkzeug muss dafür angepasst werden, und
keines kann die Warteschlange umgehen.

Du schickst also weiter das, was du ohnehin schicken würdest:

```bash
curl -s https://llm.async.example.com/v1/chat/completions \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3.6:35b",
       "messages":[{"role":"user","content":"Fasse dieses Protokoll in fünf Sätzen zusammen."}]}'
```

Zurück kommt aber nicht die Antwort, sondern in Millisekunden eine Quittung:

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

Die Verbindung ist damit sofort wieder frei. Mitgeliefert werden die Position in der Warteschlange
und eine Schätzung, wie lange es dauert — eine Hochrechnung aus den letzten
tatsächlichen Antwortzeiten desselben Modells. Keine Zusage, aber die Zahl, die man einem wartenden
Menschen zeigen kann. Abgeholt wird die Antwort später über die `poll_url` — den Weg, den die
Responses API ohnehin vorsieht.

Was sich dadurch im Betrieb ändert: Die Anfragen werden gesammelt und nach den vorhandenen
Ressourcen abgearbeitet, statt alle gleichzeitig auf das Modell loszulassen. Damit verschwindet die
Hauptursache für Ausfälle, nämlich Timeouts und Überlast durch Gleichzeitigkeit. Und weil jeder
Auftrag in der Datenbank liegt, ist eine langsame Antwort kein verlorener Auftrag mehr, sondern nur
eine Antwort, die noch nicht abgeholt wurde. Schlägt ein Aufruf fehl, wird er erneut versucht. Erst
wenn das Versuchsbudget aufgebraucht ist, landet der Auftrag sichtbar im Status `failed`, mit
Fehlermeldung statt Schweigen.

## Die Technik dahinter

Die Anwendung ist in Laravel geschrieben und legt jeden Auftrag in PostgreSQL ab. Queue-Worker holen
ihn dort ab und geben ihn der Reihe nach an das lokale Modell-Interface weiter, bei uns Ollama und
LiteLLM. Erreichbar ist das Gateway über einen Cloudflare Tunnel, gestartet wird es als
launchd-Agent direkt auf der Maschine, auf der auch die Modelle rechnen.

Der Code liegt öffentlich auf GitHub, unter MIT-Lizenz und genau so, wie wir ihn einsetzen. Dazu im
Repository: eine vollständige OpenAPI-Beschreibung der Endpunkte, eine Bruno-Sammlung zum
Ausprobieren und die Betriebskonfiguration inklusive launchd-Agents und Ingress-Regeln.

- [codebar-ag/llm-gateway.codebar.ai auf GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)
