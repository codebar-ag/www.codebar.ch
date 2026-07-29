---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: A queue, not a holding pattern — our LLM Gateway is open source
teaser: >-
  Local models answer the privacy question, not the capacity one. We built a gateway that accepts
  requests for local models, stores them and works through them in order — and we are releasing it
  under the MIT license.
published_at: 2026-07-29
published: false
author: sebastian.buergin@codebar.ch
hero: images/templates/cover-template.jpg
hero_alt: Placeholder graphic for the LLM Gateway
tags: [Open Source, KI]
featured: false
---

We work with local language models every day. The reason is unglamorous: the moment a prompt
contains customer data, contracts or internal documents, the question is no longer which model
answers best, but who gets to see the request. A model running on your own hardware answers that by
itself — nothing leaves the building, there is no processing abroad, no data processing agreement to
negotiate, and no debate about whether inputs eventually end up in someone's training run.

What local models do not come with is capacity.

## The real problem is concurrency

A local model is cheap to run and expensive to run *at the same time*. A single GPU — or a Mac's
unified memory — realistically holds one or two concurrent requests before switching between models
costs more than it saves. Ollama's `OLLAMA_NUM_PARALLEL` exists for exactly that reason: the ceiling
is hard, not soft.

Past it, requests pile up. Whether you built a queue or not makes no difference to that — it only
changes *where* the queue forms. Either somewhere you can see it, or inside Ollama's own scheduler,
where a request that is merely waiting its turn looks identical to one that is stuck.

For a single developer on a laptop, none of this matters. For internal tools that read invoices,
summarise minutes or classify documents, it quickly becomes an operational problem: one batch run
blocks everything else, a timeout in the calling system throws away an answer the model is still two
minutes away from producing, and nobody can say how long anything will take.

## What we built instead

The LLM Gateway is a queue in front of local models — and deliberately nothing else.

The flow is simple: you send your request exactly as you would send it to the model. The gateway
accepts it, writes it to the database and answers immediately with an id. As soon as capacity frees
up, a worker takes the entry, replays it upstream unchanged and puts the answer back into the same
entry. You collect it with a second call.

```bash
# submit — answers in milliseconds with an id
ID=$(curl -s https://gateway.example/v1/responses \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3-vl:30b-a3b","input":"Extract invoice number and total as JSON."}' | jq -r .id)

# collect — once the status reads "completed"
curl -s https://gateway.example/v1/responses/$ID \
  -H "Authorization: Bearer $MY_API_KEY" | jq -r '.output[0].content[0].text'
```

The API is the one OpenAI defined: `POST /v1/chat/completions` and `POST /v1/responses` behave the
way any SDK expects. The difference is in the response — instead of waiting for the model, you get a
`202` with an id. A chat completion meant to be synchronous is turned into an asynchronous job as
well. That is not a side effect but the point: a queue you can bypass is not a queue.

## Everything passes through unchanged

The thing we were most careful about while building it: the gateway does not interpret your request.

- **The body** goes upstream untouched. Unknown fields are not stripped, a missing `model` is not an
  error, and there is no validation of our own to eventually fall behind the model's actual API.
- **The headers** are stored from a fixed allowlist and replayed exactly as they arrived —
  including `Authorization` and the `X-Litellm-` tags we use to attribute cost per use case.
  Everything else is dropped rather than forwarded; a `Cookie` from a browser has no business
  reaching a model.

From which follows the second point: **the gateway holds no credentials.** There is no master key
and no user table. What authenticates the call upstream is the caller's own key — exactly as it was
when the call went direct.

Because the upstream call happens long after the original HTTP connection is gone, those headers
have to be kept somewhere. They are stored encrypted with the entry, never returned by the API,
shown on the dashboard by *name* only, and cleared the moment the entry reaches a final status. What
remains is a SHA-256 hash of the token — enough to attribute an entry to its owner, not enough to do
anything with.

:::callout{type="info" title="Who owns an entry"}
Reading, cancelling and deleting require the same key that created the entry. If it does not match,
the API answers `404` rather than `403` — it never confirms an id it would not serve anyway.
:::

## Several models, several gateways

Anyone working locally rarely has exactly one machine. In our case it is a Mac running Ollama, a
LiteLLM instance in front of several models, and — for work with nothing worth protecting in it — a
commercial provider.

Each of those targets is registered in the dashboard as a "gateway" and gets an id that sits in
front of `/v1` in the URL:

```bash
curl https://gateway.example/v1/chat/completions             # the default gateway
curl https://gateway.example/9f3c8b52-…/v1/chat/completions  # one specific gateway
```

An SDK configured with `base_url = https://gateway.example/{id}/v1` therefore talks to exactly one
target without any further changes — submitting and collecting alike. Three protocols are supported:
OpenAI-compatible (so LiteLLM, vLLM, Ollama's own shim and anything else in that shape), Anthropic,
and Ollama native. For the latter two the gateway translates out and back; for OpenAI-compatible
targets it stays a pure passthrough.

| Protocol | Endpoint upstream | Body |
| --- | --- | --- |
| OpenAI compatible | `{base}/v1/chat/completions` | passed through untouched |
| Anthropic | `{base}/v1/messages` | translated out and back |
| Ollama | `{base}/api/chat` | translated out and back |

Deactivating a gateway stops *new* requests only. Entries already queued stay bound to the target
they were assigned when they were accepted — so moving the default never redirects an answer someone
is already waiting for.

## Waiting, with a number attached

A queue that will not say how long it takes is just another black box. Every response about a
waiting entry therefore carries a block of its own:

```json
"queue": {
  "position": 3,
  "depth": 7,
  "workers": 2,
  "estimated_seconds": 84
}
```

`position` counts the entry itself, `depth` is the whole backlog, and `estimated_seconds` runs
through to *your* answer rather than to the moment work on it starts — that is the number worth
showing a person who is waiting.

It is a projection, not a promise: the median of recent actual response times for the same model,
multiplied by the number of rounds sitting in front of you. What it cannot see is a model swap — a
request that forces Ollama to load a different model takes considerably longer, and no median over
past answers knows that in advance.

## What the gateway deliberately does not do

We consider the limits the more honest half of an announcement:

- **No streaming.** A backlog and a token stream contradict each other. `stream: true` is passed
  through, but the answer then lands in the entry as a raw stream. If you want streaming, call the
  model directly.
- **Cancelling only while an entry is waiting.** A running Ollama request cannot be stopped cleanly
  — cancelling something `in_progress` would be a promise the application cannot keep.
- **No translation of tool calls.** Mapping OpenAI `tools` to Anthropic `tool_use` is a subject of
  its own and is not implemented.
- **Images are dropped on the native routes.** On the Anthropic and Ollama paths, multimodal content
  is flattened to its text, because those two carry images differently enough that guessing would be
  worse than a visibly missing image.

:::callout{type="warning" title="One worker per concurrent request"}
Running more workers than the model can answer at once merely moves the backlog out of your own
database and into Ollama's opaque queue — precisely where you can no longer see it. With Ollama the
right number is `OLLAMA_NUM_PARALLEL`, usually two.
:::

## Available under the MIT license

The gateway is a Laravel application on PostgreSQL, tested with Pest and statically analysed at
PHPStan level 10. Ours runs on a Mac under Herd, behind a Cloudflare Tunnel; the configuration for
that — launchd agents and default-deny ingress rules — lives in the repository instead of
disappearing into somebody's shell history.

We are publishing it the way we run it. It does not solve a large problem, it solves a specific one:
it makes local models predictable for internal tools, without every one of those tools having to
invent its own queue.

- [codebar-ag/llm-gateway.codebar.ai on GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)

Bug reports and pull requests are welcome. For security vulnerabilities, please do not open a public
issue — use the process described in `SECURITY.md`.
