---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: Warteschlange statt Warteschleife — unser LLM Gateway ist Open Source
teaser: >-
  Lokale Modelle lösen die Datenschutzfrage, aber nicht die Kapazitätsfrage. Wir haben ein
  Gateway gebaut, das Anfragen an lokale Modelle annimmt, speichert und der Reihe nach
  abarbeitet — und stellen es unter MIT-Lizenz zur Verfügung.
published_at: 2026-07-29
published: false
author: sebastian.buergin@codebar.ch
hero: images/templates/cover-template.jpg
hero_alt: Platzhaltergrafik zum LLM Gateway
tags: [Open Source, KI]
featured: false
---

Wir arbeiten täglich mit lokalen Sprachmodellen. Der Grund ist unspektakulär: Sobald ein Prompt
Kundendaten, Verträge oder interne Dokumente enthält, ist die Frage nicht mehr, welches Modell am
besten antwortet, sondern wer die Anfrage zu sehen bekommt. Ein Modell, das auf der eigenen
Hardware läuft, beantwortet diese Frage von selbst — nichts verlässt das Haus, es gibt keine
Verarbeitung im Ausland, keine Vertragsanhänge zur Auftragsverarbeitung, keine Diskussion darüber,
ob Eingaben irgendwann in ein Training fliessen.

Was lokale Modelle nicht mitliefern, ist Kapazität.

## Das eigentliche Problem ist Gleichzeitigkeit

Ein lokales Modell ist günstig im Betrieb und teuer in der *Parallelität*. Eine GPU — oder der
gemeinsame Speicher eines Macs — trägt realistisch ein bis zwei gleichzeitige Anfragen, bevor das
Umschalten zwischen Modellen mehr kostet, als es bringt. Ollamas `OLLAMA_NUM_PARALLEL` existiert
genau deshalb: Die Grenze ist hart, nicht weich.

Darüber hinaus stauen sich Anfragen. Ob man eine Warteschlange gebaut hat oder nicht, ändert daran
nichts — es ändert nur, *wo* sie entsteht. Entweder an einer Stelle, die man sieht, oder in Ollamas
eigenem Scheduler, wo eine Anfrage, die schlicht wartet, exakt gleich aussieht wie eine, die
hängt.

Für einen einzelnen Entwickler am Notebook ist das kein Thema. Für interne Werkzeuge, die Rechnungen
auslesen, Protokolle zusammenfassen oder Dokumente klassifizieren, wird daraus schnell ein
Betriebsproblem: Ein Batchlauf blockiert alle anderen, ein Timeout im aufrufenden System verwirft
eine Antwort, für die das Modell noch zwei Minuten rechnet, und niemand kann sagen, wie lange etwas
dauern wird.

## Was wir stattdessen gebaut haben

Das LLM Gateway ist eine Warteschlange vor den lokalen Modellen — und sonst bewusst nichts.

Der Ablauf ist einfach: Sie senden Ihre Anfrage genau so, wie Sie sie an das Modell senden würden.
Das Gateway nimmt sie an, schreibt sie in die Datenbank und antwortet sofort mit einer ID. Sobald
Kapazität frei ist, nimmt ein Worker den Eintrag, spielt ihn unverändert ans Modell weiter und legt
die Antwort zurück in denselben Eintrag. Abgeholt wird sie später über die zweite Anfrage.

```bash
# absenden — antwortet in Millisekunden mit einer ID
ID=$(curl -s https://gateway.example/v1/responses \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3-vl:30b-a3b","input":"Rechnungsnummer und Total als JSON."}' | jq -r .id)

# abholen — sobald der Status auf «completed» steht
curl -s https://gateway.example/v1/responses/$ID \
  -H "Authorization: Bearer $MY_API_KEY" | jq -r '.output[0].content[0].text'
```

Die API ist die von OpenAI definierte: `POST /v1/chat/completions` und `POST /v1/responses`
funktionieren so, wie es jedes SDK erwartet. Der Unterschied liegt in der Antwort — statt auf das
Modell zu warten, kommt ein `202` mit einer ID zurück. Auch eine als synchron gedachte
Chat-Completion wird dabei in einen asynchronen Auftrag umgewandelt. Das ist kein Nebeneffekt,
sondern der Zweck: Eine Warteschlange, die man umgehen kann, ist keine.

## Alles geht 1:1 durch

Der Punkt, auf den wir beim Bauen am meisten geachtet haben: Das Gateway interpretiert die Anfrage
nicht.

- **Der Body** geht unverändert nach oben. Unbekannte Felder werden nicht entfernt, ein fehlendes
  `model` ist kein Fehler, es gibt keine eigene Validierung, die irgendwann hinter der API des
  Modells zurückbleibt.
- **Die Header** werden aus einer festen Positivliste gespeichert und exakt so wieder gesendet, wie
  sie ankamen — inklusive `Authorization` und der `X-Litellm-`-Tags, mit denen wir Kosten pro
  Anwendungsfall auswerten. Alles andere wird verworfen statt weitergereicht; ein `Cookie` aus einem
  Browser hat beim Modell nichts zu suchen.

Daraus folgt der zweite Punkt: **Das Gateway besitzt keine Zugangsdaten.** Es gibt keinen
Master-Key und keine Benutzertabelle. Was den Aufruf beim Modell legitimiert, ist der Schlüssel des
Aufrufenden — so wie vorher auch, als noch direkt gerufen wurde.

Weil der Aufruf nach oben erst stattfindet, wenn die ursprüngliche HTTP-Verbindung längst geschlossen
ist, müssen die Header zwischengelagert werden. Sie liegen verschlüsselt beim Eintrag, werden von der
API nie zurückgegeben, im Dashboard nur mit *Namen* angezeigt und gelöscht, sobald der Eintrag einen
Endzustand erreicht. Zurück bleibt ein SHA-256-Hash des Tokens — genug, um einen Eintrag seinem
Urheber zuzuordnen, zu wenig, um damit etwas anzustellen.

:::callout{type="info" title="Wem ein Eintrag gehört"}
Lesen, abbrechen und löschen darf nur, wer denselben Schlüssel schickt, mit dem der Eintrag erstellt
wurde. Passt er nicht, antwortet die API mit `404` statt mit `403` — sie bestätigt eine ID nicht, die
sie ohnehin nicht ausliefern würde.
:::

## Mehrere Modelle, mehrere Gateways

Wer lokal arbeitet, hat selten genau eine Maschine. Bei uns sind es ein Mac mit Ollama, eine
LiteLLM-Instanz vor mehreren Modellen und, für Aufgaben ohne schützenswerten Inhalt, ein
kommerzieller Anbieter.

Jedes dieser Ziele wird im Dashboard als «Gateway» erfasst und bekommt eine ID, die vor dem `/v1`
in der URL steht:

```bash
curl https://gateway.example/v1/chat/completions          # das Standard-Gateway
curl https://gateway.example/9f3c8b52-…/v1/chat/completions  # ein bestimmtes
```

Ein SDK, das mit `base_url = https://gateway.example/{id}/v1` konfiguriert ist, spricht damit ohne
weitere Anpassung genau ein Ziel an — absenden und abholen. Drei Protokolle werden unterstützt:
OpenAI-kompatibel (also LiteLLM, vLLM, Ollamas eigener Shim und alles andere in dieser Form),
Anthropic und Ollama nativ. Bei den letzten beiden übersetzt das Gateway hin und zurück; bei
OpenAI-kompatiblen Zielen bleibt es reiner Durchgang.

| Protokoll | Endpunkt beim Ziel | Body |
| --- | --- | --- |
| OpenAI-kompatibel | `{base}/v1/chat/completions` | unverändert durchgereicht |
| Anthropic | `{base}/v1/messages` | übersetzt hin und zurück |
| Ollama | `{base}/api/chat` | übersetzt hin und zurück |

Ein Gateway zu deaktivieren stoppt nur *neue* Anfragen. Bereits eingereihte Einträge sind fest mit
dem Ziel verbunden, das sie bei der Annahme zugewiesen bekommen haben — ein Wechsel des Standards
lenkt also nie eine Antwort um, auf die jemand gerade wartet.

## Warten mit Anzeige

Eine Warteschlange, die nicht sagt, wie lange es dauert, ist auch nur eine Blackbox. Jede Antwort zu
einem wartenden Eintrag enthält deshalb einen eigenen Block:

```json
"queue": {
  "position": 3,
  "depth": 7,
  "workers": 2,
  "estimated_seconds": 84
}
```

`position` zählt den Eintrag selbst mit, `depth` ist der gesamte Rückstand, und `estimated_seconds`
rechnet bis zur *eigenen* Antwort, nicht bis zum Beginn der Bearbeitung — das ist die Zahl, die man
einem wartenden Menschen zeigen kann.

Sie ist eine Hochrechnung, keine Zusage: der Median der letzten tatsächlichen Antwortzeiten für
dasselbe Modell, multipliziert mit der Anzahl Runden, die vor einem liegen. Was sie nicht sehen
kann, ist ein Modellwechsel — eine Anfrage, die Ollama zwingt, ein anderes Modell zu laden, dauert
deutlich länger, und kein Median über vergangene Antworten weiss davon im Voraus.

## Was das Gateway bewusst nicht tut

Wir halten die Grenzen für den ehrlicheren Teil einer Ankündigung:

- **Kein Streaming.** Ein Rückstand und ein Token-Strom widersprechen sich. `stream: true` wird
  zwar durchgereicht, die Antwort landet dann aber als Rohstrom im Eintrag. Wer Streaming braucht,
  ruft das Modell direkt.
- **Abbrechen nur, solange etwas wartet.** Eine laufende Ollama-Anfrage lässt sich nicht sauber
  stoppen — ein Abbruch im Zustand `in_progress` wäre ein Versprechen, das die Anwendung nicht
  halten kann.
- **Keine Übersetzung von Tool-Calls.** Die Abbildung zwischen OpenAI-`tools` und Anthropic-`tool_use`
  ist ein Thema für sich und ist nicht umgesetzt.
- **Bilder fallen auf den nativen Routen weg.** Auf den Wegen zu Anthropic und Ollama werden
  multimodale Inhalte auf ihren Text reduziert, weil beide Bilder unterschiedlich genug
  transportieren, dass Raten schlechter wäre als eine sichtbare Lücke.

:::callout{type="warning" title="Ein Worker pro paralleler Anfrage"}
Mehr Worker zu starten, als das Modell gleichzeitig beantworten kann, verschiebt den Rückstand
lediglich aus der eigenen Datenbank in Ollamas undurchsichtige Warteschlange — also genau dorthin,
wo man ihn nicht mehr sieht. Bei Ollama entspricht die richtige Zahl `OLLAMA_NUM_PARALLEL`, in der
Regel zwei.
:::

## Verfügbar unter MIT-Lizenz

Das Gateway ist eine Laravel-Anwendung mit PostgreSQL, getestet mit Pest und statisch geprüft auf
PHPStan-Level 10. Es läuft bei uns auf einem Mac unter Herd, hinter einem Cloudflare Tunnel; die
Konfiguration dafür — launchd-Agents und Ingress-Regeln mit Default-Deny — liegt im Repository,
statt in der Shell-History von jemandem zu verschwinden.

Wir veröffentlichen es so, wie wir es einsetzen. Es löst kein grosses Problem, sondern ein
konkretes: Es macht lokale Modelle für interne Werkzeuge planbar, ohne dass jedes dieser Werkzeuge
seine eigene Warteschlange erfinden muss.

- [codebar-ag/llm-gateway.codebar.ai auf GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)

Fehlerberichte und Pull Requests sind willkommen. Sicherheitslücken bitte nicht über ein
öffentliches Issue, sondern auf dem in `SECURITY.md` beschriebenen Weg.
