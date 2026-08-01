---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: 'Running local LLMs: queues instead of timeouts'
teaser: >-
  Running models in-house pays off – until requests grow and your own resources turn into the
  bottleneck. Here's what we built against it.
published_at: 2026-07-31
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/llm-gateway-open-source.svg
hero_alt: Concurrent requests now run through a queue that a gateway works off one at a time
thumb: images/news/llm-gateway-open-source-card.svg
tags: [Open Source, KI]
featured: false
---

## Six months of AI in our daily work

For about six months now, no topic has occupied us as much as AI. We have tried a lot in that time,
discarded a fair amount of it again, and found a handful of things that genuinely help in our
day-to-day work. Over the coming weeks and months we would like to show more of that.

We are starting with a small tool we built for ourselves, because something kept getting in our way.
More interesting than the tool, though, is the problem behind it — and everyone running models
in-house has it.

We, codebar Solutions AG, build custom software and look after document management systems for Swiss
SMEs and large companies, from the initial concept through implementation to day-to-day operation.
Where things have actually changed for us:

- **Concept and prototyping.** Prototypes come together faster. Our customers got to see something
  before as well, it simply took longer. Today we get through considerably more rounds in the same
  time: show a state, collect feedback, adjust, show it again. Those iterations are the real gain.
  Decisions are made earlier, and the next step becomes clear sooner.
- **Custom software development.** Anyone who expected a single prompt to produce the perfect result
  will be disappointed. Anyone who already had clean processes and applies AI deliberately to the
  individual steps benefits considerably. What remains essential is the person in the loop: clarify
  requirements, check results, review code. Nobody takes that off your hands.
- **DMS and ECM.** Extracting document data and multi-agent workflows mean that fewer and fewer
  documents ever need to be touched by hand.

Behind all three areas sits the same thing in the end: a model doing the computing. Where it does
that was a deliberate decision for us from the outset.

## Control and data sovereignty

It was clear to us early on that we wanted our own models on our own infrastructure. The moment a
prompt contains customer data, contracts or internal documents, the question is no longer which
model answers best, but who gets to see the request. If the model runs in-house, that question
answers itself.

On top of that comes a point that looked different only a year ago: local models are now capable
enough to handle most of our tasks without trouble. The real challenge lies elsewhere, namely in
asking the right questions and giving good instructions. Anyone who invests time in solid prompt
engineering will often get there with a local model too.

We keep this deliberately pragmatic. Our models run in-house on a single machine with 128 GB of
unified memory, on protected power. It runs Ollama and LiteLLM, the models we need for our tasks,
plus the tooling for analysis and operations.

- [codebar.ch/ai/llm](https://www.codebar.ch/ai/llm) — which models we run
- [codebar.ch/ai/llm-analytics](https://www.codebar.ch/ai/llm-analytics) — what they actually get to
  do

## Where it starts to bind

We do not work with one perfect model, but with several. Agents run in our workflows, and each agent
gets the model that suits its task: extracting data from receipts calls for a different one than
writing a summary, and a classification is handled by a small, fast model for which a large one
would be overkill. On top of that come the requests from the team and from running processes. In the
end, everything points at the same machine.

And that is exactly where the limit is. A model has to be loaded before it can answer, and that
occupies memory. Our models need anywhere between a few
GB and over 100 GB of it, the largest one around 102 GB on its own. Then there is the context: the
longer the request and the more documents sent along with it, the more is added on top. That leaves
little of the 128 GB — enough for exactly one task to be worked on at a time. And every switch
to a different model means loading first, computing second. Anything beyond that does not achieve
more, it merely makes everything slower.

Without orchestration, requests fail. Agents, processes and the people working with the models run
into timeouts, and nobody can say whether something is still computing or was lost long ago.

## What we built

A brief look at the technology, because it explains the core of it: in practice, the format defined
by OpenAI has established itself as the common denominator. Almost every local inference solution —
Ollama, LiteLLM, vLLM, llama.cpp — offers an OpenAI-compatible endpoint, even when there is no
OpenAI behind it at all. The usual call, `POST /v1/chat/completions`, is synchronous: you send the
request and hold the connection open until the answer arrives.

For precisely this problem the same standard already provides an answer: the newer Responses API.
Instead of going to `POST /v1/chat/completions`, the request goes to `POST /v1/responses`, an
ID comes back immediately, and the result is collected later via `GET /v1/responses/{id}` — exactly
the asynchronous behaviour we need. You cannot rely on it, though: by no means every model and every
endpoint supports it, and even where it is available, the calling application has to explicitly ask
for it. Anyone who does not, or cannot, keeps sending synchronously — and ends up back in the
holding pattern.

That is the order we reversed. It is not the calling tool that decides whether work happens
asynchronously, it is the queue: our application sits between the calling tools and the local model
interface and turns every request into an asynchronous job, including one meant to be
synchronous. No tool has to be adapted for it, and none can bypass the queue.

So you keep sending what you would send anyway:

```bash
curl -s https://llm.async.example.com/v1/chat/completions \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3.6:35b",
       "messages":[{"role":"user","content":"Summarise these minutes in five sentences."}]}'
```

What comes back is not the answer, however, but a receipt, in milliseconds:

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

The connection is free again immediately. What comes with it is the position in the queue and an
estimate of how long it will take — a projection from the most recent actual response times for
the same model. Not a promise, but the number you can show a person who is waiting. The answer is
collected later via the `poll_url` — the route the Responses API provides for anyway.

What changes in operation: requests are collected and worked through according to the resources
available, instead of all being let loose on the model at once. That removes the main cause of
failures, namely timeouts and overload through concurrency. And because every job sits in the
database, a slow answer is no longer a lost job, merely an answer that has not been collected yet.
If a call fails, it is retried. Only once the retry budget is used up does the job land visibly in
the `failed` status, with an error message instead of silence.

## The technology behind it

The application is written in Laravel and puts every job into PostgreSQL. Queue workers pick it up
from there and pass it on to the local model interface one at a time, in our case Ollama and
LiteLLM. The gateway is reachable through a Cloudflare Tunnel, and it is started as a launchd agent
directly on the machine the models run on.

The code is public on GitHub, under the MIT license and exactly as we use it. Also in the
repository: a complete OpenAPI description of the endpoints, a Bruno collection for trying it out,
and the operating configuration including launchd agents and ingress rules.

- [codebar-ag/llm-gateway.codebar.ai on GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)
